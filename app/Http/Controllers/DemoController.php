<?php

namespace App\Http\Controllers;

use App\Events\AppOpened;
use App\Services\DemoAppLauncher;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class DemoController extends Controller
{
    private const APPS = [
        'calculadora' => 'calc.exe',
        'bloc' => 'notepad.exe',
        'paint' => 'mspaint.exe',
    ];

    public function abrir(Request $request, DemoAppLauncher $launcher, string $app): JsonResponse
    {
        abort_unless(config('app.demo_apps') && PHP_OS_FAMILY === 'Windows', 403, 'La demo está desactivada.');

        $executable = self::APPS[$app] ?? abort(404);

        $launcher->launch($executable);

        AppOpened::dispatch($app, $request->ip() ?? 'unknown', now()->format('H:i:s'));

        return response()->json(['ok' => true]);
    }
}
