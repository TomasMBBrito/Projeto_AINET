<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserManagementController;
use app\Http\Controllers\AuthController;

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
});

require __DIR__.'/auth.php';
