<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserManagementController;
use app\Http\Controllers\AuthController;
use App\Http\Controllers\CatalogController;
use App\Http\Controllers\OrdersStockController;

// Página inicial
Route::get('/', function () {
    return view('welcome');
})->name('home');

// Rotas autenticadas e verificadas
Route::middleware(['auth', 'verified'])->group(function () {

    // Dashboard
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    // Perfil
    Route::get('/profile', [ProfileController::class, 'show'])->name('profile.show');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::put('/profile/password', [ProfileController::class, 'changePassword'])->name('profile.password');

    // Gestão de utilizadores (apenas direção)
    Route::middleware('can:manageUsers')->group(function () {
        Route::get('/admin/users', [UserManagementController::class, 'index'])->name('users.index');
        Route::get('/admin/users/create', [UserManagementController::class, 'create'])->name('users.create');
        Route::post('/admin/users', [UserManagementController::class, 'store'])->name('users.store');
        Route::get('/admin/users/{user}/edit', [UserManagementController::class, 'edit'])->name('users.edit');
        Route::put('/admin/users/{user}', [UserManagementController::class, 'update'])->name('users.update');
        Route::delete('/admin/users/{user}', [UserManagementController::class, 'destroy'])->name('users.destroy');
        Route::put('/admin/users/{user}/block', [UserManagementController::class, 'toggleBlock'])->name('users.block');
        Route::put('/admin/users/{user}/board', [UserManagementController::class, 'toggleBoard'])->name('users.toggleBoard');
        Route::put('/admin/users/restore/{id}', [UserManagementController::class, 'restore'])->name('users.restore');
    });

// Rota pública para o catálogo (acessível a todos)
Route::get('/catalog', [CatalogController::class, 'index'])->name('catalog.public');

// Gestão de catálogo (mantidas para usuários autenticados)
Route::middleware('auth')->group(function () {
    Route::get('/catalog', [CatalogController::class, 'index'])->name('catalog.index');
    Route::post('/cart/add', [CatalogController::class, 'addToCart'])->name('cart.add');
});

// Gestão de encomendas e stock (mantidas para usuários autenticados)
Route::middleware('auth')->group(function () {
    Route::get('/orders-stock', [OrdersStockController::class, 'index'])->name('orders-stock.index');
    Route::get('/orders-stock/create', [OrdersStockController::class, 'create'])->name('orders-stock.create');
    Route::post('/orders-stock', [OrdersStockController::class, 'store'])->name('orders-stock.store');
    Route::get('/orders-stock/{order}/edit', [OrdersStockController::class, 'edit'])->name('orders-stock.edit');
    Route::put('/orders-stock/{order}', [OrdersStockController::class, 'update'])->name('orders-stock.update');
    Route::delete('/orders-stock/{order}', [OrdersStockController::class, 'destroy'])->name('orders-stock.destroy');
});

});

require __DIR__.'/auth.php';
