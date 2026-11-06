<?php

use App\Http\Controllers\DemoController;
use App\Http\Controllers\SecurityDashboardController;
use App\Http\Controllers\SecurityLoginController;
use App\Http\Controllers\ServerController;
use App\Http\Middleware\SecurityLoginGuard;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/servidor', [ServerController::class, 'index']);
Route::get('/graficos', fn () => view('graficos'));

Route::post('/demo/abrir/{app}', [DemoController::class, 'abrir'])
    ->whereIn('app', ['calculadora', 'bloc', 'paint'])
    ->middleware('throttle:2,1');

// Ruta del formulario de login
Route::get('/security-login', function () {
    return view('security-login');
})->name('login');

// Procesamiento de login con middleware de guardado de intento
Route::post('/security-login', [SecurityLoginController::class, 'login'])
    ->middleware(SecurityLoginGuard::class);

// Ruta del Panel de Monitoreo
Route::get('/security-dashboard', [SecurityDashboardController::class, 'index']);
