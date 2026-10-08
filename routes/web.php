<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ContactoController;
use App\Http\Controllers\CustomerOrderController;
use App\Http\Controllers\PublicPageController;
use App\Http\Controllers\ReservaController;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

// Vistas públicas dinámicas (con datos de BD y fallback a config)
Route::get('/', [PublicPageController::class, 'home'])->name('welcome');
Route::view('/nosotros', 'nosotros')->name('nosotros');
Route::get('/carta', [PublicPageController::class, 'carta'])->name('carta');
Route::get('/galeria', [PublicPageController::class, 'galeria'])->name('galeria');

// Reservas
Route::get('/reserva', [ReservaController::class, 'index'])->name('reserva');
Route::post('/reserva', [ReservaController::class, 'store'])->middleware('throttle:10,1')->name('reserva.store');

// Venta Web (Sprint A) & Pagos (Sprint B)
use App\Http\Controllers\OrderCheckoutController;
use App\Http\Controllers\PaymentController;

Route::get('/checkout', [OrderCheckoutController::class, 'showCheckout'])->name('checkout');
Route::post('/pedido/crear', [OrderCheckoutController::class, 'store'])->middleware('throttle:10,1')->name('pedido.store');
Route::get('/pedido/{order_number}', [OrderCheckoutController::class, 'confirmation'])->name('pedido.confirmacion');
Route::post('/pedido/{order_number}/pagar', [PaymentController::class, 'processPayment'])->middleware('throttle:10,1')->name('pedido.pagar');
Route::post('/pedido/{order_number}/reembolsar', [PaymentController::class, 'refundPayment'])->middleware(['auth', 'role:admin'])->name('pedido.reembolsar');

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

use App\Http\Controllers\AdminCatalogController;
use App\Http\Controllers\AdminDashboardController;
use App\Http\Controllers\AdminMessageController;
use App\Http\Controllers\AdminOperationsController;
use App\Http\Controllers\AdminSettingsController;
use App\Http\Controllers\AdminUserController;

// ── Rutas del Administrador ─────────────────────────────
Route::prefix('admin')
    ->middleware(['auth', 'role:admin'])
    ->name('admin.')
    ->group(function () {
        Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

        // Productos
        Route::get('/productos', [AdminCatalogController::class, 'products'])->name('products.index');
        Route::post('/productos', [AdminCatalogController::class, 'storeProduct'])->name('products.store');
        Route::put('/productos/{id}', [AdminCatalogController::class, 'updateProduct'])->name('products.update');
        Route::patch('/productos/{id}/toggle', [AdminCatalogController::class, 'toggleProductStatus'])->name('products.toggle');
        Route::delete('/productos/{id}', [AdminCatalogController::class, 'destroyProduct'])->name('products.destroy');

        // Categorías
        Route::get('/categorias', [AdminCatalogController::class, 'categories'])->name('categories.index');
        Route::post('/categorias', [AdminCatalogController::class, 'storeCategory'])->name('categories.store');
        Route::put('/categorias/{id}', [AdminCatalogController::class, 'updateCategory'])->name('categories.update');
        Route::delete('/categorias/{id}', [AdminCatalogController::class, 'destroyCategory'])->name('categories.destroy');

        // Reservas
        Route::get('/reservas', [AdminOperationsController::class, 'reservations'])->name('reservations.index');
        Route::patch('/reservas/{id}/status', [AdminOperationsController::class, 'updateReservationStatus'])->name('reservations.status');
        Route::delete('/reservas/{id}', [AdminOperationsController::class, 'destroyReservation'])->name('reservations.destroy');

        // Órdenes
        Route::get('/ordenes', [AdminOperationsController::class, 'orders'])->name('orders.index');
        Route::patch('/ordenes/{id}/status', [AdminOperationsController::class, 'updateOrderStatus'])->name('orders.status');

        // Mensajes de contacto
        Route::get('/mensajes', [AdminMessageController::class, 'index'])->name('messages.index');
        Route::patch('/mensajes/{id}/status', [AdminMessageController::class, 'updateStatus'])->name('messages.status');
        Route::delete('/mensajes/{id}', [AdminMessageController::class, 'destroy'])->name('messages.destroy');

        // Usuarios
        Route::get('/usuarios', [AdminUserController::class, 'index'])->name('users.index');
        Route::patch('/usuarios/{id}/role', [AdminUserController::class, 'updateRole'])->name('users.role');

        // Galería
        Route::get('/galeria', [AdminCatalogController::class, 'gallery'])->name('gallery.index');
        Route::post('/galeria', [AdminCatalogController::class, 'storeGallery'])->name('gallery.store');
        Route::put('/galeria/{id}', [AdminCatalogController::class, 'updateGallery'])->name('gallery.update');
        Route::delete('/galeria/{id}', [AdminCatalogController::class, 'destroyGallery'])->name('gallery.destroy');

        // Mesas 3D
        Route::get('/mesas', [AdminOperationsController::class, 'tables'])->name('tables.index');
        Route::patch('/mesas/{id}/status', [AdminOperationsController::class, 'updateTableStatus'])->name('tables.status');

        // Ajustes Generales
        Route::get('/ajustes', [AdminSettingsController::class, 'index'])->name('settings.index');
        Route::post('/ajustes', [AdminSettingsController::class, 'update'])->name('settings.update');
    });

// ── Rutas del Cliente ───────────────────────────────────
Route::prefix('mi-cuenta')
    ->middleware(['auth', 'role:customer'])
    ->name('customer.')
    ->group(function () {
        Route::get('/pedidos', [CustomerOrderController::class, 'index'])->name('orders');
    });
