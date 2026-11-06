<?php

namespace App\Http\Controllers;

use App\Models\LoginAttempt;
use Illuminate\Support\Facades\DB;

class SecurityDashboardController extends Controller
{
    public function index()
    {
        // 1. Tres indicadores de las últimas 24 horas (Requerimiento TP)
        $since = now()->subDay();
        $stats = [];

        foreach (['success', 'failed', 'blocked'] as $status) {
            $stats[$status] = LoginAttempt::where('status', $status)
                ->where('created_at', '>=', $since)
                ->count();
        }

        // 2. IPs con 3 o más fallos en los últimos 5 minutos (Consigna estricta del TP)
        $suspiciousIps = LoginAttempt::select('ip_address', DB::raw('COUNT(*) as total_fallos'))
            ->where('status', 'failed')
            ->where('created_at', '>=', now()->subMinutes(5))
            ->groupBy('ip_address')
            ->havingRaw('COUNT(*) >= 3')
            ->orderByDesc('total_fallos')
            ->get();

        return view('security-dashboard', compact('stats', 'suspiciousIps'));
    }
}
