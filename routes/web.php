<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// Routes without sanctum middleware for testing views

Route::prefix('admin')->group(function () {
    Route::apiResource('credit-packages', \App\Http\Controllers\Admin\CreditPackageController::class)
        ->only(['index', 'store', 'update', 'destroy'])
        ->names('admin.credit-packages');
});

Route::prefix('admin')->group(function () {
    Route::get('/dashboard', [\App\Http\Controllers\Admin\DashboardController::class, 'index'])->name('admin.dashboard');
});

Route::prefix('admin')->group(function () {
    Route::resource('users', \App\Http\Controllers\Admin\UserController::class)
        ->names('admin.users');
});

Route::prefix('admin')->group(function () {
    Route::resource('products', \App\Http\Controllers\Admin\ProductController::class)->names('admin.products');
    Route::get('products/{id}', [\App\Http\Controllers\Admin\ProductController::class, 'show'])->name('admin.products.show');
    Route::post('products/{id}/toggle-offer-pool', [\App\Http\Controllers\Admin\ProductController::class, 'toggleOfferPool']);
});

Route::get('/rag', [\App\Http\Controllers\RagController::class, 'index'])->name('rag.index');
Route::post('/rag/ask', [\App\Http\Controllers\RagController::class, 'ask'])->name('rag.ask');