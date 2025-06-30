<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\User\{
    CartController, OrderController, PackageController, RagController, RagChatController,
    HomeController, ProductSearchController, AIRecommendationController
};

// Redirect root to login
Route::get('/', fn () => redirect()->route('login'));

// Dashboard (authenticated)
Route::get('/dashboard', fn () => view('dashboard'))->middleware(['auth', 'verified'])->name('dashboard');

// ==========================
// 🔒 Authenticated User Profile Routes
// ==========================
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// ==========================
// 🔐 Admin Routes
// ==========================
Route::middleware(['auth', 'role:admin|SuperAdmin,web'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [App\Http\Controllers\Admin\AdminDashboardController::class, 'index'])->name('dashboard');

    Route::resource('products', App\Http\Controllers\Admin\ProductController::class);
    Route::resource('packages', App\Http\Controllers\Admin\CreditPackageController::class);
    Route::resource('orders', App\Http\Controllers\Admin\OrderController::class);

    Route::get('search', [App\Http\Controllers\Admin\AdminSearchController::class, '__invoke'])->name('search');
    Route::get('rag', [App\Http\Controllers\Admin\RagController::class, 'index'])->name('rag');
    Route::post('rag/chat', [App\Http\Controllers\Admin\RagChatController::class, 'chat']);
    Route::get('users/history', [App\Http\Controllers\Admin\UserHistoryController::class, 'index'])->name('users.history');
    Route::get('users', [App\Http\Controllers\Admin\UserController::class, 'index'])->name('users.index');
});

// ==========================
// 👑 SuperAdmin-only Routes
// ==========================
Route::middleware(['auth', 'role:SuperAdmin,web'])->prefix('admin/superadmin')->name('admin.superadmin.')->group(function () {
    Route::get('users', [App\Http\Controllers\Admin\UserController::class, 'superadminUserManagement'])->name('users');
    Route::post('users/{id}/impersonate', [App\Http\Controllers\Admin\UserController::class, 'impersonate'])->name('users.impersonate');
    Route::post('users/stop-impersonate', [App\Http\Controllers\Admin\UserController::class, 'stopImpersonate'])->name('users.stopImpersonate');
});

// ==========================
// 👤 User Routes
// ==========================
  Route::middleware(['auth', 'role:user,web'])->prefix('user')->name('user.')->group(function () {
    
    // Home
    Route::get('/home', [HomeController::class, 'index'])->name('dashboard');
    Route::get('/products/{product}', [HomeController::class, 'show'])->name('products.show');

    // Cart
    Route::get('cart', [CartController::class, 'showCart'])->name('cart.show');
    Route::post('cart/add', [CartController::class, 'add'])->name('cart.add');
    Route::post('cart/update/{id}/{action}', [CartController::class, 'updateQuantity'])->name('cart.update');
    Route::get('cart/get', [CartController::class, 'getCart'])->name('cart.get');
    Route::delete('cart/remove/{id}', [CartController::class, 'remove'])->name('cart.remove');
    Route::post('cart/redeem/{id}', [CartController::class, 'redeem'])->name('cart.redeem');

    // Orders
    Route::get('orders', [OrderController::class, 'index'])->name('orders.index');
    Route::get('orders/{order}', [OrderController::class, 'show'])->name('orders.show');
    Route::post('orders', [OrderController::class, 'store'])->name('orders.store');
    Route::delete('orders/{order}', [OrderController::class, 'destroy'])->name('orders.destroy');
    Route::get('orders/history', [OrderController::class, 'history'])->name('orders.history');
    Route::get('orders/create', [OrderController::class, 'create'])->name('orders.create');
    Route::post('orders/{order}/update', [OrderController::class, 'update'])->name('orders.update');
    Route::get('orders/{order}/edit', [OrderController::class, 'edit'])->name('orders.edit');

    // Packages
    Route::get('packages', [PackageController::class, 'index'])->name('packages.index');
    Route::post('packages/buy/{id}', [PackageController::class, 'buy'])->name('packages.buy');
    Route::get('packages/history', [PackageController::class, 'history'])->name('packages.history');

     Route::get('rag', [App\Http\Controllers\User\RagController::class, 'index'])->name('rag');
    Route::post('rag/chat', [App\Http\Controllers\User\RagChatController::class, 'chat']);
});

// ==========================
// 🌍 Public Routes
// ==========================
Route::get('/user/home', [HomeController::class, 'index'])->name('user.home');
Route::get('/user/products/{product}', [HomeController::class, 'show'])->name('user.products.show');

Route::get('/user/cart', [CartController::class, 'showCart'])->name('user.cart.show');
Route::post('/user/cart/add', [CartController::class, 'add'])->name('user.cart.add');
Route::post('/user/cart/update/{id}/{action}', [CartController::class, 'updateQuantity'])->name('user.cart.update');
Route::get('/user/cart/get', [CartController::class, 'getCart'])->name('user.cart.get');
Route::delete('/user/cart/remove/{id}', [CartController::class, 'remove'])->name('user.cart.remove');
Route::post('/user/cart/redeem/{id}', [CartController::class, 'redeem'])->name('user.cart.redeem');

// ==========================
// 🔎 Global Search
// ==========================
Route::get('/search', [\App\Http\Controllers\SearchController::class, 'index'])->name('search.index');
Route::get('/search/autocomplete', [\App\Http\Controllers\SearchController::class, 'autocomplete'])->name('search.autocomplete');

// ==========================
// 🧭 Redirect After Login
// ==========================
Route::get('/redirect-after-login', function () {
    if (Auth::check()) {
        $user = Auth::user();
        if ($user->hasRole('SuperAdmin')) {
            return redirect()->route('admin.dashboard');
        }
        if ($user->hasRole('admin')) {
            return redirect()->route('admin.dashboard');
        }
        return redirect()->route('user.dashboard');
    }
    return redirect('/login');
})->name('redirect.after.login');

// ==========================
// 🔐 Auth & Error Routes
// ==========================
require __DIR__.'/auth.php';

Route::fallback(fn () => response()->view('errors.404', [], 404));
Route::get('/unauthorized', fn () => response()->view('errors.unauthorized', [], 403))->name('unauthorized');
