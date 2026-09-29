<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ContactoController;
use App\Http\Controllers\ReservaController;

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CustomerOrderController;

// Vistas estáticas públicas
Route::view('/', 'welcome')->name('welcome');
Route::view('/nosotros', 'nosotros')->name('nosotros');
Route::view('/carta', 'carta')->name('carta');
Route::view('/galeria', 'galeria')->name('galeria');

// Reservas
Route::get('/reserva', [ReservaController::class, 'index'])->name('reserva');
Route::post('/reserva', [ReservaController::class, 'store'])->name('reserva.store');

// Contacto
Route::get('/contacto', [ContactoController::class, 'index'])->name('contacto');
Route::post('/contacto', [ContactoController::class, 'store'])->name('contacto.store');

// Autenticación pública
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Redirección genérica /dashboard según el rol del usuario autenticado
Route::get('/dashboard', function () {
    return auth()->user()->isAdmin()
        ? redirect()->route('admin.dashboard')
        : redirect()->route('customer.orders');
})->middleware('auth')->name('dashboard');

// ── Rutas del Administrador ─────────────────────────────
Route::prefix('admin')
    ->middleware(['auth', 'role:admin'])
    ->name('admin.')
    ->group(function () {
        Route::get('/dashboard', [AuthController::class, 'dashboard'])->name('dashboard');
    });

// ── Rutas del Cliente ───────────────────────────────────
Route::prefix('mi-cuenta')
    ->middleware(['auth', 'role:customer'])
    ->name('customer.')
    ->group(function () {
        Route::get('/pedidos', [CustomerOrderController::class, 'index'])->name('orders');
    });


