<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\User\CartController;
use App\Http\Controllers\User\OrderController;
use App\Http\Controllers\User\PackageController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;
use App\Http\Controllers\User\RagController;
use App\Http\Controllers\User\RagChatController;

// Redirect root to login page
Route::get('/', function () {
    return redirect()->route('login');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// Authenticated user profile routes
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Admin routes
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [App\Http\Controllers\Admin\AdminDashboardController::class, 'index'])->name('dashboard');
    Route::resource('products', App\Http\Controllers\Admin\ProductController::class);
    Route::resource('packages', App\Http\Controllers\Admin\CreditPackageController::class);
    Route::resource('orders', App\Http\Controllers\Admin\OrderController::class);
    Route::get('search', [\App\Http\Controllers\Admin\AdminSearchController::class, '__invoke'])->name('search');

    // Admin RAG
    Route::get('rag', [\App\Http\Controllers\Admin\RagController::class, 'index'])->name('rag');

    // Admin chatbot endpoint
    Route::post('rag/chat', [\App\Http\Controllers\Admin\RagChatController::class, 'chat']);

    // Admin: User history (orders + package purchases)
    Route::get('users/history', [\App\Http\Controllers\Admin\UserHistoryController::class, 'index'])->name('users.history');

    // Admin: User list with orders
    Route::get('users', [\App\Http\Controllers\Admin\UserController::class, 'index'])->name('users.index');
});

// User routes
Route::middleware(['auth', 'role:user'])->prefix('user')->name('user.')->group(function () {
    Route::get('/home', [App\Http\Controllers\User\HomeController::class, 'index'])->name('dashboard');
    Route::get('/products/{product}', [App\Http\Controllers\User\HomeController::class, 'show'])->name('products.show');

    // Cart routes
    Route::get('cart', [CartController::class, 'showCart'])->name('cart.show');
    Route::post('cart/add', [CartController::class, 'add'])->name('cart.add');
    Route::post('cart/update/{id}/{action}', [CartController::class, 'updateQuantity'])->name('cart.update');
    Route::get('cart/get', [CartController::class, 'getCart'])->name('cart.get');
    Route::delete('cart/remove/{id}', [CartController::class, 'remove'])->name('cart.remove');
    Route::post('cart/redeem/{id}', [CartController::class, 'redeem'])->name('cart.redeem');

    // Orders and packages
    Route::resource('orders', OrderController::class);
    Route::get('orders/history', [OrderController::class, 'history'])->name('orders.history');
    Route::get('packages', [PackageController::class, 'index'])->name('packages.index');
    Route::post('packages/buy/{id}', [PackageController::class, 'buy'])->name('packages.buy');
    Route::get('packages/history', [PackageController::class, 'history'])->name('packages.history');

    // User RAG
     Route::get('rag', [\App\Http\Controllers\User\RagController::class, 'index'])->name('rag.index');
    Route::post('rag/chat', [\App\Http\Controllers\User\RagChatController::class, 'chat'])->name('rag.chat');

    // Balance checker (optional)
    Route::get('balance', function() {
        $user = Auth::user();
        return 'Current balance: $' . ($user ? $user->credit_balance : 'N/A');
    });

    // Product search endpoint (GET /user/products/search?query=...)
    Route::get('products/search', [\App\Http\Controllers\User\ProductSearchController::class, 'search'])->name('products.search');
    // AI recommendation endpoint (POST /user/ai/recommendation)
    Route::post('ai/recommendation', [\App\Http\Controllers\User\AIRecommendationController::class, 'recommend'])->name('ai.recommendation');
});

// Make /user/home and product browsing public, but require login for checkout
Route::get('/user/home', [\App\Http\Controllers\User\HomeController::class, 'index'])->name('user.home');
Route::get('/user/products/{product}', [\App\Http\Controllers\User\HomeController::class, 'show'])->name('user.products.show');

// Cart routes (add, update, get) are public, but checkout requires login
Route::get('/user/cart', [\App\Http\Controllers\User\CartController::class, 'showCart'])->name('user.cart.show');
Route::post('/user/cart/add', [\App\Http\Controllers\User\CartController::class, 'add'])->name('user.cart.add');
Route::post('/user/cart/update/{id}/{action}', [\App\Http\Controllers\User\CartController::class, 'updateQuantity'])->name('user.cart.update');
Route::get('/user/cart/get', [\App\Http\Controllers\User\CartController::class, 'getCart'])->name('user.cart.get');
Route::delete('/user/cart/remove/{id}', [\App\Http\Controllers\User\CartController::class, 'remove'])->name('user.cart.remove');
Route::post('/user/cart/redeem/{id}', [\App\Http\Controllers\User\CartController::class, 'redeem'])->name('user.cart.redeem');

// User order resource routes (with explicit names for all actions)
// Remove duplicate or conflicting user order routes
// Only keep the following inside the user group:
Route::middleware(['auth', 'role:user'])->prefix('user')->name('user.')->group(function () {
    Route::get('/home', [App\Http\Controllers\User\HomeController::class, 'index'])->name('dashboard');
    Route::get('/products/{product}', [App\Http\Controllers\User\HomeController::class, 'show'])->name('products.show');
    Route::get('cart', [CartController::class, 'showCart'])->name('cart.show');
    Route::post('cart/add', [CartController::class, 'add'])->name('cart.add');
    Route::post('cart/update/{id}/{action}', [CartController::class, 'updateQuantity'])->name('cart.update');
    Route::get('cart/get', [CartController::class, 'getCart'])->name('cart.get');
    Route::delete('cart/remove/{id}', [CartController::class, 'remove'])->name('cart.remove');
    Route::post('cart/redeem/{id}', [CartController::class, 'redeem'])->name('cart.redeem');
    Route::resource('orders', OrderController::class);
    Route::get('orders/history', [OrderController::class, 'history'])->name('orders.history');
    Route::get('packages', [PackageController::class, 'index'])->name('packages.index');
    Route::post('packages/buy/{id}', [PackageController::class, 'buy'])->name('packages.buy');
    Route::get('packages/history', [PackageController::class, 'history'])->name('packages.history');
    Route::get('rag', [\App\Http\Controllers\User\RagController::class, 'index'])->name('rag.index');
    Route::post('rag/chat', [\App\Http\Controllers\User\RagChatController::class, 'chat'])->name('rag.chat');
    Route::get('balance', function() {
        $user = Auth::user();
        return 'Current balance: $' . ($user ? $user->credit_balance : 'N/A');
    });
    Route::get('products/search', [\App\Http\Controllers\User\ProductSearchController::class, 'search'])->name('products.search');
    Route::post('ai/recommendation', [\App\Http\Controllers\User\AIRecommendationController::class, 'recommend'])->name('ai.recommendation');
});

// Global search
Route::get('/search', [\App\Http\Controllers\SearchController::class, 'index'])->name('search.index');
Route::get('/search/autocomplete', [\App\Http\Controllers\SearchController::class, 'autocomplete'])->name('search.autocomplete');

// Fallback route for 404 Not Found
Route::fallback(function () {
    return response()->view('errors.404', [], 404);
});

// Optional: Custom unauthorized route
Route::get('/unauthorized', function () {
    return response()->view('errors.unauthorized', [], 403);
})->name('unauthorized');

// Redirect after login based on role
Route::get('/redirect-after-login', function () {
    if (Auth::check()) {
        $user = Auth::user();
        if (method_exists($user, 'hasRole') && $user->hasRole('admin')) {
            return redirect()->route('admin.dashboard');
        }
        // If user does not have hasRole, fallback to role column or default
        if (property_exists($user, 'role') && $user->role === 'admin') {
            return redirect()->route('admin.dashboard');
        }
        return redirect()->route('user.dashboard', [], false)->setTargetUrl('/user/home');
    }
    return redirect('/login');
})->name('redirect.after.login');

// Auth routes
require __DIR__.'/auth.php';

// PERFORMANCE OPTIMIZATION SUGGESTIONS
// 1. Remove duplicate user routes (already done)
// 2. Use route caching in production
// 3. Use config and view caching in production
// 4. Use Redis for cache/session if available
// 5. Add DB indexes for common queries (see migration suggestions)
// 6. Use eager loading in controllers (already present in most places)
// 7. Use pagination everywhere (already present)
// 8. Use CDN/minified assets (already present)
// 9. Set production env in .env
// 10. Optimize images before upload
// 11. Remove unused composer/npm packages
// 12. Use Laravel's optimize command
//
// To apply runtime optimizations, run these commands after deploy:
// php artisan config:cache
// php artisan route:cache
// php artisan view:cache
// php artisan optimize
//
// To use Redis, set in .env:
// CACHE_DRIVER=redis
// SESSION_DRIVER=redis
//
// To add DB indexes, add to your migrations (example for products table):
// $table->index('category');
// $table->index('user_id');
// $table->index('created_at');
