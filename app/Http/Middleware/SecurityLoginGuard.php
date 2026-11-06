<?php

namespace App\Http\Middleware;

use App\Models\LoginAttempt;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class SecurityLoginGuard
{
    public function handle(Request $request, Closure $next)
    {
        $ip = $request->ip();
        $key = 'security:block:' . hash('sha256', $ip);

        // 1. Si la IP ya está guardada en caché como bloqueada
        if (Cache::has($key)) {
            LoginAttempt::create([
                'ip_address' => $ip,
                'status'     => 'blocked',
                'user_agent' => substr($request->userAgent() ?? '', 0, 255),
                'created_at' => now(),
            ]);

            Log::channel('security')->warning('Blocked login attempt', ['ip' => $ip]);

            abort(429, 'Acceso temporalmente restringido');
        }

        // 2. Contar intentos fallidos de esta IP en los últimos 5 minutos
        $failures = LoginAttempt::where('ip_address', $ip)
            ->where('status', 'failed')
            ->where('created_at', '>=', now()->subMinutes(5))
            ->count();

        // 3. Evaluar umbrales de seguridad
        if ($failures >= 5) {
            Cache::put($key, true, now()->addMinutes(10));

            Log::channel('security')->critical('Temporary IP block', [
                'ip'       => $ip,
                'failures' => $failures
            ]);

            abort(429, 'Acceso temporalmente restringido');
        }

        if ($failures >= 3) {
            Log::channel('security')->alert('Possible brute force', [
                'ip'       => $ip,
                'failures' => $failures
            ]);
        }

        return $next($request);
    }
}
