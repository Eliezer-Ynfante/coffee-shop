<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/nosotros', function () {
    return view('nosotros');
});

Route::get('/carta', function () {
    return view('carta');
});

Route::get('/reserva', function () {
    return view('reserva');
})->name('reserva');

Route::post('/reserva', function () {
    return back()->with('status', 'Reserva procesada.');
})->name('reserva.post');


Route::get('/galeria', function () {
    return view('galeria');
});

Route::get('/contacto', function () {
    return view('contacto');
});

Route::get('/login', function () {
    return view('auth.login');
})->name('login');

Route::post('/login', function () {
    // Procesamiento o redirección de login
    return back()->with('status', 'Credenciales recibidas.');
})->name('login.post');


