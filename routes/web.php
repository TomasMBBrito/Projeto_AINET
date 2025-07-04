<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Http\Controllers\{
    AuthController,
    ProfileController,
    UserManagementController,
    CatalogController,
    HomeController,
    OrderController,
    SupplyOrderController,
    CategoryController,
    ProductController,
    BusinessSettingsController,
    ShippingCostController,
    CartController,
    CardController,
    MembershipFeeController,
    PurchaseController,
    StatisticsController,
    FavoriteController
};

// Home page
Route::get('/', [HomeController::class, 'index'])->name('home');

Route::middleware('guest')->group(function () {
    // Login
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);

    // Sign up
    Route::get('/register', [AuthController::class, 'showRegistrationForm'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);

    // password reset
    Route::get('/password/reset', [AuthController::class, 'showForgotPasswordForm'])->name('password.request');
    Route::post('/password/email', [AuthController::class, 'sendResetLinkEmail'])->name('password.email');
    Route::get('/password/reset/{token}', [AuthController::class, 'showResetForm'])->name('password.reset');
    Route::post('/password/reset', [AuthController::class, 'reset'])->name('password.update');
});

Route::middleware('auth')->group(function () {
    Route::post('logout', [AuthController::class, 'logout'])->name('logout');

    Route::get('/email/verify', [AuthController::class, 'showVerificationNotice'])->name('verification.send');

    Route::post('/email/resend', [AuthController::class, 'resendVerificationEmail'])->name('verification.resend');

    Route::post('/email/check', [AuthController::class, 'checkEmailVerified'])->name('verification.check');

    Route::get('/email/verify/{id}/{hash}', [AuthController::class, 'verify'])
        ->middleware(['auth', 'signed', 'throttle:6,1'])
        ->name('verification.verify');
});

// Rotas públicas (acessíveis a todos)
Route::get('/catalog', [CatalogController::class, 'index'])->name('catalog.index');
Route::get('/catalog/product/{id}', [CatalogController::class, 'show'])->name('catalog.product.show');
Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
Route::post('/cart/add', [CartController::class, 'add'])->name('cart.add');
Route::post('/cart/update', [CartController::class, 'update'])->name('cart.update');
Route::post('/cart/remove', [CartController::class, 'remove'])->name('cart.remove');
Route::post('/cart/clear-cart', [CartController::class, 'clearCart'])->name('cart.clear-cart');
Route::post('/cart/checkout', [CartController::class, 'checkout'])->name('cart.checkout');
Route::get('/cart/confirm', [CartController::class, 'showConfirm'])->name('cart.confirm');
Route::post('/cart/finalize', [CartController::class, 'finalize'])->name('cart.finalize');
Route::view('/about', 'pages.about')->name('about');
Route::view('/contact', 'pages.contact')->name('contact');
Route::view('/faq', 'pages.faq')->name('faq');

// Rotas autenticadas e verificadas
Route::middleware(['auth', 'verified'])->group(function () {

    // Perfil
    Route::get('/profile', [ProfileController::class, 'show'])->name('profile.show');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::put('/profile/password', [ProfileController::class, 'changePassword'])->name('profile.password');
    Route::delete('/profile/photo', [ProfileController::class, 'removePhoto'])->name('profile.removePhoto');

    Route::get('/favorites', [FavoriteController::class, 'index'])->name('favorites.index');
    Route::post('/favorites/toggle', [FavoriteController::class, 'toggle'])->name('favorites.toggle');

    Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/{order}', [OrderController::class, 'show'])->name('orders.show');
    Route::post('/orders/{order}/complete', [OrderController::class, 'complete'])->name('orders.complete');
    Route::get('/orders/{order}/cancel', [OrderController::class, 'showCancelForm'])->name('orders.cancel.form');
    Route::post('/orders/{order}/cancel', [OrderController::class, 'cancel'])->name('orders.cancel');
    Route::get('/orders/{order}/invoice', [OrderController::class, 'generateInvoice'])->name('orders.invoice');
    Route::get('/orders/create', [OrderController::class, 'create'])->name('orders.create');
    Route::post('/orders', [OrderController::class, 'store'])->name('orders.store');
    Route::get('/orders/{order}/edit', [OrderController::class, 'edit'])->name('orders.edit');
    Route::put('/orders/{order}', [OrderController::class, 'update'])->name('orders.update');
    Route::delete('/orders/{order}', [OrderController::class, 'destroy'])->name('orders.destroy');

    // Gestão do website (apenas direção)
    Route::get('/admin/users', [UserManagementController::class, 'index'])->name('users.index');
    Route::get('/admin/users/create', [UserManagementController::class, 'create'])->name('users.create');
    Route::post('/admin/users', [UserManagementController::class, 'store'])->name('users.store');
    Route::get('/admin/users/{user}/edit', [UserManagementController::class, 'edit'])->name('users.edit');
    Route::put('/admin/users/{user}', [UserManagementController::class, 'update'])->name('users.update');
    Route::delete('/admin/users/{user}', [UserManagementController::class, 'destroy'])->name('users.destroy');
    Route::put('/admin/users/{user}/block', [UserManagementController::class, 'toggleBlock'])->name('users.block');
    Route::put('/admin/users/{user}/board', [UserManagementController::class, 'toggleBoard'])->name('users.toggleBoard');
    Route::put('/admin/users/restore/{id}', [UserManagementController::class, 'restore'])->name('users.restore');

    Route::get('/categories', [CategoryController::class, 'index'])->name('categories.index');
    Route::get('/categories/create', [CategoryController::class, 'create'])->name('categories.create');
    Route::get('/categories/{category}', [CategoryController::class, 'edit'])->name('categories.edit');
    Route::post('/categories/store', [CategoryController::class, 'store'])->name('categories.store');
    Route::put('/categories/{category}', [CategoryController::class, 'update'])->name('categories.update');
    Route::delete('/categories/{category}', [CategoryController::class, 'destroy'])->name('categories.destroy');
    Route::post('/categories/restore/{id}', [CategoryController::class, 'restore'])->name('categories.restore');

    Route::get('/products', [ProductController::class, 'index'])->name('products.index');
    Route::get('/products/create', [ProductController::class, 'create'])->name('products.create');
    Route::post('/products/store', [ProductController::class, 'store'])->name('products.store');
    Route::get('/products/{product}', [ProductController::class, 'edit'])->name('products.edit');
    Route::put('/products/{product}', [ProductController::class, 'update'])->name('products.update');
    Route::delete('/products/{product}', [ProductController::class, 'destroy'])->name('products.destroy');
    Route::post('/products/restore/{id}', [ProductController::class, 'restore'])->name('products.restore');

    Route::get('membership_fee', [BusinessSettingsController::class, 'edit'])->name('settings.edit');
    Route::post('/membership_fee', [BusinessSettingsController::class, 'update'])->name('settings.update');

    // Quota de membro
    Route::get('/membership/pay', [MembershipFeeController::class, 'showPayMembership'])->name('membership.pay');
    Route::post('/membership/process', [MembershipFeeController::class, 'processMembershipFee'])->name('membership.process');

    // Custos de envio
    Route::get('/admin/settings/shipping-costs', [ShippingCostController::class, 'index'])->name('admin.settings.shipping_costs.index');
    Route::get('/admin/settings/shipping-costs/create', [ShippingCostController::class, 'create'])->name('admin.settings.shipping_costs.create');
    Route::post('/admin/settings/shipping-costs/store', [ShippingCostController::class, 'store'])->name('admin.settings.shipping_costs.store');
    Route::get('/admin/settings/shipping-costs/{shippingCost}/edit', [ShippingCostController::class, 'edit'])->name('admin.settings.shipping_costs.edit');
    Route::put('/admin/settings/shipping-costs/{shippingCost}', [ShippingCostController::class, 'update'])->name('admin.settings.shipping_costs.update');
    Route::delete('/admin/settings/shipping-costs/{shippingCost}', [ShippingCostController::class, 'destroy'])->name('admin.settings.shipping_costs.destroy');

    Route::patch('/orders/{order}/complete', [OrderController::class, 'complete'])->name('orders.complete')
        ->middleware('can:complete,order');
    Route::patch('/orders/{order}/cancel', [OrderController::class, 'cancel'])->name('orders.cancel')
        ->middleware('can:cancel,order');

    // Supply Orders routes
    Route::get('/supply-orders', [SupplyOrderController::class, 'index'])->name('supply-orders.index');
    Route::post('/supply-orders', [SupplyOrderController::class, 'store'])->name('supply-orders.store');
    Route::patch('/supply-orders/{supplyOrder}/complete', [SupplyOrderController::class, 'complete'])->name('supply-orders.complete');
    Route::delete('/supply-orders/{supplyOrder}', [SupplyOrderController::class, 'destroy'])->name('supply-orders.destroy');

    // Purchase routes
    Route::get('/purchases', [PurchaseController::class, 'index'])->name('purchase.index');

    // Card routes
    Route::get('/card', [CardController::class, 'index'])->name('card.index');
    Route::get('/card/credit', [CardController::class, 'showCreditForm'])->name('card.credit');
    Route::post('/card/credit', [CardController::class, 'credit'])->name('card.credit');
    Route::get('/card/transactions', [CardController::class, 'transactions'])->name('card.transactions');

    Route::get('/statistics', [StatisticsController::class, 'index'])->name('statistics');
    Route::get('/statistics/exportXLSX', [StatisticsController::class, 'exportSalesXLSX'])->name('statistics.exportXLSX');
    Route::get('/statistics/exportCSV', [StatisticsController::class, 'exportSalesCSV'])->name('statistics.exportCSV');

});
