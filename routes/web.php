<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ContactoController;
use App\Http\Controllers\ReservaController;

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

// Autenticación / Portal
Route::view('/login', 'auth.login')->name('login');
Route::post('/login', function () {
    return back()->with('status', 'Portal en modo demostración.');
})->name('login.post');


