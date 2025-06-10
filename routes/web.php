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
    OrdersStockController,
    CategoryController,
    ProductController,
    BusinessSettingsController,
    ShippingCostController,
    CartController,
    CardController,
    MembershipFeeController
};


// Página inicial
Route::get('/', [HomeController::class, 'index'])->name('home');

Route::middleware('guest')->group(function () {
    //login
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);

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

    // Página de aviso para verificação
    Route::get('/email/verify', [AuthController::class, 'showVerificationNotice'])->name('verification.send');

    // Reenviar email de verificação
    Route::post('/email/resend', [AuthController::class, 'resendVerificationEmail'])->name('verification.resend');

    // Botão "Já verifiquei"
    Route::post('/email/check', [AuthController::class, 'checkEmailVerified'])->name('verification.check');

    Route::get('/email/verify/{id}/{hash}', [AuthController::class, 'verify'])
        ->middleware(['auth', 'signed', 'throttle:6,1'])
        ->name('verification.verify');

});

// Rotas públicas (acessíveis a todos)
Route::get('/catalog', [CatalogController::class, 'index'])->name('catalog.index');
Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
//Route::post('/cart/add', [CartController::class, 'add'])->name('cart.add');
Route::post('/cart/add/', [CartController::class, 'add'])->name('cart.add');
Route::post('/cart/update', [CartController::class, 'update'])->name('cart.update');
Route::post('/cart/remove', [CartController::class, 'remove'])->name('cart.remove');
//Route::post('/cart/clear', [CartController::class, 'clear'])->name('cart.clear');
Route::post('/cart/clear-cart', [CartController::class, 'clearCart'])->name('cart.clear-cart');


Route::view('/sobre', 'pages.about')->name('about');
Route::view('/contact', 'pages.about')->name('contact');
Route::view('/faq', 'pages.about')->name('faq');

// Rotas autenticadas e verificadas
Route::middleware(['auth', 'verified'])->group(function () {

    // Perfil
    Route::get('/profile', [ProfileController::class, 'show'])->name('profile.show');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::put('/profile/password', [ProfileController::class, 'changePassword'])->name('profile.password');

    Route::get('/orders-stock', [OrdersStockController::class, 'index'])->name('orders-stock.index');
    Route::get('/orders-stock/create', [OrdersStockController::class, 'create'])->name('orders-stock.create');
    Route::post('/orders-stock', [OrdersStockController::class, 'store'])->name('orders-stock.store');
    Route::get('/orders-stock/{order}/edit', [OrdersStockController::class, 'edit'])->name('orders-stock.edit');
    Route::put('/orders-stock/{order}', [OrdersStockController::class, 'update'])->name('orders-stock.update');
    Route::delete('/orders-stock/{order}', [OrdersStockController::class, 'destroy'])->name('orders-stock.destroy');

    //Route::get('/test-gate', [UserManagementController::class, 'testGate']);

    // Gestão do website (apenas direção)
    //Route::middleware('can:manageSite')->group(function () {
    Route::get('/admin/users', [UserManagementController::class, 'index'])->name('users.index');
    Route::get('/admin/users/create', [UserManagementController::class, 'create'])->name('users.create');
    Route::post('/admin/users', [UserManagementController::class, 'store'])->name('users.store');
    Route::get('/admin/users/{user}/edit', [UserManagementController::class, 'edit'])->name('users.edit');
    Route::put('/admin/users/{user}', [UserManagementController::class, 'update'])->name('users.update');
    Route::delete('/admin/users/{user}', [UserManagementController::class, 'destroy'])->name('users.destroy');
    Route::put('/admin/users/{user}/block', [UserManagementController::class, 'toggleBlock'])->name('users.block');
    Route::put('/admin/users/{user}/board', [UserManagementController::class, 'toggleBoard'])->name('users.toggleBoard');
    Route::put('/admin/users/restore/{id}', [UserManagementController::class, 'restore'])->name('users.restore');

    Route::get('/categories', [CategoryController::class, 'index'])->name('categories.index');  //Página index das categorias
    Route::get('/categories/create', [CategoryController::class, 'create'])->name('categories.create'); //Criar categoria
    Route::get('/categories/{category}', [CategoryController::class, 'edit'])->name('categories.edit'); //Editar categoria
    Route::post('/categories/store', [CategoryController::class, 'store'])->name('categories.store');   //Armazenar categoria
    Route::put('/categories/{category}', [CategoryController::class, 'update'])->name('categories.update'); //Atualizar categoria
    Route::delete('/categories/{category}', [CategoryController::class, 'destroy'])->name('categories.destroy');    //Eliminar categoria
    Route::post('/categories/restore/{id}', [CategoryController::class, 'restore'])->name('categories.restore');    //Restaurar categoria -> ainda não funciona

    //Produtos | Feito c/filtragem
    Route::get('/products', [ProductController::class, 'index'])->name('products.index');   //Página index dos produtos
    Route::get('/products/create', [ProductController::class, 'create'])->name('products.create'); //Criar produto
    Route::post('/products/store', [ProductController::class, 'store'])->name('products.store'); //Armazena produto
    Route::get('/products/{product}', [ProductController::class, 'edit'])->name('products.edit'); //Editar produto
    Route::put('/products/{product}', [ProductController::class, 'update'])->name('products.update'); //Atualizar produto
    Route::delete('/products/{product}', [ProductController::class, 'destroy'])->name('products.destroy'); //Eliminar produto
    Route::post('/products/restore/{id}', [ProductController::class, 'restore'])->name('products.restore'); //Eliminação reversível do produto (restaurar)

    //Taxa de adesão | Feito
    Route::get('membership_fee', [BusinessSettingsController::class, 'edit'])->name('settings.edit');
    Route::post('/membership_fee', [BusinessSettingsController::class, 'update'])->name('settings.update');

    // Cartão Virtual
    Route::get('/card/create', [CardController::class, 'showCreate'])->name('card.create');
    Route::post('/card/create', [CardController::class, 'storeCreate']);
    Route::get('/card/topup', [CardController::class, 'showTopup'])->name('card.topup');
    Route::post('/card/topup', [CardController::class, 'processTopup']);

    // Quota de membro
    Route::get('/membership/pay', [MembershipFeeController::class, 'showPayMembership'])->name('membership.pay');
    Route::post('/membership/process', [MembershipFeeController::class, 'processMembershipFee'])->name('membership.process');

    //Custos de envio
    Route::get('/admin/settings/shipping-costs', [ShippingCostController::class, 'index'])->name('admin.settings.shipping_costs.index'); // Lista de custos de envio
    Route::get('/admin/settings/shipping-costs/create', [ShippingCostController::class, 'create'])->name('admin.settings.shipping_costs.create'); // Criar novo custo de envio
    Route::post('/admin/settings/shipping-costs/store', [ShippingCostController::class, 'store'])->name('admin.settings.shipping_costs.store'); // Armazenar novo custo de envio
    Route::get('/admin/settings/shipping-costs/{shippingCost}/edit', [ShippingCostController::class, 'edit'])->name('admin.settings.shipping_costs.edit'); // Editar custo de envio
    Route::put('/admin/settings/shipping-costs/{shippingCost}', [ShippingCostController::class, 'update'])->name('admin.settings.shipping_costs.update'); // Atualizar custo de envio existente
    Route::delete('/admin/settings/shipping-costs/{shippingCost}', [ShippingCostController::class, 'destroy'])->name('admin.settings.shipping_costs.destroy');

    Route::post('/cart/checkout', [CartController::class, 'checkout'])->name('cart.checkout');

    // Página de confirmação da compra (checkout)
    Route::get('/cart/confirm', [CartController::class, 'showConfirm'])->name('cart.confirm');

    // Confirmar e finalizar compra
    Route::post('/cart/confirm', [CartController::class, 'finalize'])->name('cart.process');

});




//});
//Route::post('/cart/add', [CatalogController::class, 'addToCart'])->name('cart.add');

// // Gestão de catálogo (mantidas para usuários autenticados)
// Route::middleware('auth')->group(function () {
//     // Route::get('/catalog', [CatalogController::class, 'index'])->name('catalog.index');

// });

// Gestão de encomendas e stock (mantidas para usuários autenticados)
// Route::middleware('auth')->group(function () {
//     Route::get('/orders-stock', [OrdersStockController::class, 'index'])->name('orders-stock.index');
//     Route::get('/orders-stock/create', [OrdersStockController::class, 'create'])->name('orders-stock.create');
//     Route::post('/orders-stock', [OrdersStockController::class, 'store'])->name('orders-stock.store');
//     Route::get('/orders-stock/{order}/edit', [OrdersStockController::class, 'edit'])->name('orders-stock.edit');
//     Route::put('/orders-stock/{order}', [OrdersStockController::class, 'update'])->name('orders-stock.update');
//     Route::delete('/orders-stock/{order}', [OrdersStockController::class, 'destroy'])->name('orders-stock.destroy');
// });

//BussinesSettings
// Route::middleware(['auth'])->group(function () {
//     //Categorias | Feito
//     Route::get('/categories', [CategoryController::class, 'index'])->name('categories.index');  //Página index das categorias
//     Route::get('/categories/create', [CategoryController::class, 'create'])->name('categories.create'); //Criar categoria
//     Route::get('/categories/{category}', [CategoryController::class, 'edit'])->name('categories.edit'); //Editar categoria
//     Route::post('/categories/store', [CategoryController::class, 'store'])->name('categories.store');   //Armazenar categoria
//     Route::put('/categories/{category}', [CategoryController::class, 'update'])->name('categories.update'); //Atualizar categoria
//     Route::delete('/categories/{category}', [CategoryController::class, 'destroy'])->name('categories.destroy');    //Eliminar categoria
//     Route::post('/categories/restore/{id}', [CategoryController::class, 'restore'])->name('categories.restore');    //Restaurar categoria -> ainda não funciona

//     //Produtos | Feito c/filtragem
//     Route::get('/products', [ProductController::class, 'index'])->name('products.index');   //Página index dos produtos
//     Route::get('/products/create', [ProductController::class, 'create'])->name('products.create'); //Criar produto
//     Route::post('/products/store', [ProductController::class, 'store'])->name('products.store'); //Armazena produto
//     Route::get('/products/{product}', [ProductController::class, 'edit'])->name('products.edit'); //Editar produto
//     Route::put('/products/{product}', [ProductController::class, 'update'])->name('products.update'); //Atualizar produto
//     Route::delete('/products/{product}', [ProductController::class, 'destroy'])->name('products.destroy'); //Eliminar produto
//     Route::post('/products/restore/{id}', [ProductController::class, 'restore'])->name('products.restore'); //Eliminação reversível do produto (restaurar)

//     //Taxa de adesão | Feito
//     Route::get('general', [BusinessSettingsController::class, 'edit'])->name('settings.edit');
//     Route::post('general', [BusinessSettingsController::class, 'update'])->name('settings.update');

//     //Custos de envio
//     //Route::get('/shipping-costs', [ShippingCostController::class, 'index'])->name('shipping-costs.index');
// });
