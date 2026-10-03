<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ContactoController;
use App\Http\Controllers\ReservaController;

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CustomerOrderController;

use App\Http\Controllers\PublicPageController;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

// Vistas públicas dinámicas (con datos de BD y fallback a config)
Route::get('/', [PublicPageController::class, 'home'])->name('welcome');
Route::view('/nosotros', 'nosotros')->name('nosotros');
Route::get('/carta', [PublicPageController::class, 'carta'])->name('carta');
Route::get('/galeria', [PublicPageController::class, 'galeria'])->name('galeria');

// Reservas
Route::get('/reserva', [ReservaController::class, 'index'])->name('reserva');
Route::post('/reserva', [ReservaController::class, 'store'])->middleware('throttle:10,1')->name('reserva.store');

// Contacto
Route::get('/contacto', [ContactoController::class, 'index'])->name('contacto');
Route::post('/contacto', [ContactoController::class, 'store'])->middleware('throttle:10,1')->name('contacto.store');

// Autenticación pública
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->middleware('throttle:5,1')->name('login.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Redirección genérica /dashboard según el rol del usuario autenticado
Route::get('/dashboard', function () {
    $user = Auth::user();
    if (! $user instanceof User) {
        abort(403);
    }

    return $user->isAdmin()
        ? redirect()->route('admin.dashboard')
        : redirect()->route('customer.orders');
})->middleware('auth')->name('dashboard');

use App\Http\Controllers\AdminController;

// ── Rutas del Administrador ─────────────────────────────
Route::prefix('admin')
    ->middleware(['auth', 'role:admin'])
    ->name('admin.')
    ->group(function () {
        Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');

        // Productos
        Route::get('/productos', [AdminController::class, 'products'])->name('products.index');
        Route::post('/productos', [AdminController::class, 'storeProduct'])->name('products.store');
        Route::put('/productos/{id}', [AdminController::class, 'updateProduct'])->name('products.update');
        Route::patch('/productos/{id}/toggle', [AdminController::class, 'toggleProductStatus'])->name('products.toggle');
        Route::delete('/productos/{id}', [AdminController::class, 'destroyProduct'])->name('products.destroy');

        // Categorías
        Route::get('/categorias', [AdminController::class, 'categories'])->name('categories.index');
        Route::post('/categorias', [AdminController::class, 'storeCategory'])->name('categories.store');
        Route::put('/categorias/{id}', [AdminController::class, 'updateCategory'])->name('categories.update');
        Route::delete('/categorias/{id}', [AdminController::class, 'destroyCategory'])->name('categories.destroy');

        // Reservas
        Route::get('/reservas', [AdminController::class, 'reservations'])->name('reservations.index');
        Route::patch('/reservas/{id}/status', [AdminController::class, 'updateReservationStatus'])->name('reservations.status');
        Route::delete('/reservas/{id}', [AdminController::class, 'destroyReservation'])->name('reservations.destroy');

        // Órdenes
        Route::get('/ordenes', [AdminController::class, 'orders'])->name('orders.index');
        Route::patch('/ordenes/{id}/status', [AdminController::class, 'updateOrderStatus'])->name('orders.status');

        // Mensajes de contacto
        Route::get('/mensajes', [AdminController::class, 'messages'])->name('messages.index');
        Route::patch('/mensajes/{id}/status', [AdminController::class, 'updateMessageStatus'])->name('messages.status');
        Route::delete('/mensajes/{id}', [AdminController::class, 'destroyMessage'])->name('messages.destroy');

        // Usuarios
        Route::get('/usuarios', [AdminController::class, 'users'])->name('users.index');
        Route::patch('/usuarios/{id}/role', [AdminController::class, 'updateUserRole'])->name('users.role');

        // Galería
        Route::get('/galeria', [AdminController::class, 'gallery'])->name('gallery.index');
        Route::post('/galeria', [AdminController::class, 'storeGallery'])->name('gallery.store');
        Route::put('/galeria/{id}', [AdminController::class, 'updateGallery'])->name('gallery.update');
        Route::delete('/galeria/{id}', [AdminController::class, 'destroyGallery'])->name('gallery.destroy');

        // Mesas 3D
        Route::get('/mesas', [AdminController::class, 'tables'])->name('tables.index');
        Route::patch('/mesas/{id}/status', [AdminController::class, 'updateTableStatus'])->name('tables.status');

        // Ajustes Generales
        Route::get('/ajustes', [AdminController::class, 'settings'])->name('settings.index');
        Route::post('/ajustes', [AdminController::class, 'updateSettings'])->name('settings.update');
    });

// ── Rutas del Cliente ───────────────────────────────────
Route::prefix('mi-cuenta')
    ->middleware(['auth', 'role:customer'])
    ->name('customer.')
    ->group(function () {
        Route::get('/pedidos', [CustomerOrderController::class, 'index'])->name('orders');
    });


