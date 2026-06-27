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
});

Route::get('/galeria', function () {
    return view('galeria');
});

Route::get('/contacto', function () {
    return view('contacto');
});

Route::get('/login', function () {
    return view('auth.login');
});

