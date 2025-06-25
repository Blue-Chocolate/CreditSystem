<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\User\CartController;
use App\Http\Controllers\User\OrderController;
use App\Http\Controllers\User\PackageController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [App\Http\Controllers\Admin\AdminDashboardController::class, 'index'])->name('dashboard');
    Route::resource('products', App\Http\Controllers\Admin\ProductController::class);
    Route::resource(('packages'), App\Http\Controllers\Admin\CreditPackageController::class);
    Route::resource('orders', App\Http\Controllers\Admin\OrderController::class);
    Route::get('search', [\App\Http\Controllers\Admin\AdminSearchController::class, '__invoke'])->name('search');
});

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
    Route::get('packages', [PackageController::class, 'index'])->name('packages.index');
    Route::post('packages/buy/{id}', [PackageController::class, 'buy'])->name('packages.buy');
    Route::post('orders/store', [App\Http\Controllers\User\OrderController::class, 'store'])->name('orders.store');
    Route::get('orders/history', [OrderController::class, 'history'])->name('orders.history');
    Route::get('packages/history', [PackageController::class, 'history'])->name('packages.history');
});
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

require __DIR__.'/auth.php';
