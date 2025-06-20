<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});
Route::prefix('admin')->middleware(['auth:sanctum'])->group(function () {
    Route::apiResource('credit-packages', \App\Http\Controllers\Admin\CreditPackageController::class)->only([
        'index', 'store', 'update', 'destroy'
    ]);
});
// routes/web.php or routes/admin.php

Route::prefix('admin')->middleware(['auth:sanctum'])->group(function () {
    Route::get('/dashboard', [\App\Http\Controllers\Admin\DashboardController::class, 'index'])->name('admin.dashboard');
});
Route::prefix('admin')->middleware(['auth:sanctum'])->group(function () {
    Route::resource('users', \App\Http\Controllers\Admin\UserController::class)
        ->names('admin.users');
});
Route::prefix('admin')->middleware(['auth:sanctum'])->group(function () {
    Route::resource('products', \App\Http\Controllers\Admin\ProductController::class)->names('admin.products');
});