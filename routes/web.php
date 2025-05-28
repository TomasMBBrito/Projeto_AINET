<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use App\Http\Controllers\{
    AuthController,
    ProfileController,
    UserManagementController,
    CatalogController,
    HomeController,
    OrdersStockController
};


// Página inicial
Route::get('/', [HomeController::class, 'index'])->name('home');

Route::middleware('guest')->group(function () {
    //login
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // Registo
    Route::get('/register', [AuthController::class, 'showRegistrationForm'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);

    // Recuperação de password
    Route::get('/password/reset', [AuthController::class, 'showForgotPasswordForm'])->name('password.request');
    Route::post('/password/email', [AuthController::class, 'sendResetLinkEmail'])->name('password.email');

    Route::get('/password/reset/{token}', [AuthController::class, 'showResetForm'])->name('password.reset');
    Route::post('/password/reset', [AuthController::class, 'reset'])->name('password.update');

});

Route::middleware('auth')->group(function () {
    Route::post('logout', [AuthController::class, 'logout'])->name('logout');

    // Verificação de email
    Route::get('email/verify', [AuthController::class, 'showVerificationNotice'])->name('verification.notice');

    Route::get('email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
        $request->fulfill();
        return redirect('/');
    })->middleware(['auth', 'signed'])->name('verification.verify');

    Route::post('email/verification-notification', function (Request $request) {
        $request->user()->sendEmailVerificationNotification();
        return back()->with('message', 'Email de verificação reenviado com sucesso!');
    })->middleware(['auth', 'throttle:6,1'])->name('verification.send');
    
    Route::post('/email/check', function () {
        $user = Auth::user();
        if (! $user->email_verified_at == '') {
            return redirect()->route('login')->with('status', 'Email verificado com sucesso. Pode iniciar sessão.');
        }

        return back()->withErrors(['email' => 'Ainda não foi feita a verificação do email. Verifique a sua caixa de entrada.']);
    })->middleware('auth')->name('verification.check');
    
});

// Rota pública para o catálogo (acessível a todos)
Route::get('/catalog', [CatalogController::class, 'index'])->name('catalog.index');
Route::view('/sobre', 'pages.about')->name('about');
Route::view('/contact', 'pages.about')->name('contact');
Route::view('/faq', 'pages.about')->name('faq');

// Rotas autenticadas e verificadas
Route::middleware(['auth', 'verified'])->group(function () {

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


//Route::post('/cart/add', [CatalogController::class, 'addToCart'])->name('cart.add');

// // Gestão de catálogo (mantidas para usuários autenticados)
// Route::middleware('auth')->group(function () {
//     // Route::get('/catalog', [CatalogController::class, 'index'])->name('catalog.index');
    
// });

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
