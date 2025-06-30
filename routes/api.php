<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CartController;
use App\Http\Controllers\Api\OrderController;
use App\Http\Controllers\Api\PackageController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::middleware('api')->get('/ping', function (Request $request) {
    return response()->json(['pong' => true]);
});

// Public API endpoints
Route::get('products', [ProductController::class, 'index']);
Route::get('products/{product}', [ProductController::class, 'show']);
Route::get('packages', [PackageController::class, 'index']);
// Advanced product search and AI recommendations
Route::get('products/search', [ProductController::class, 'search']);
Route::get('products/recommend', [ProductController::class, 'recommend']);

// Auth endpoints
Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);

// Protected API endpoints (require auth:sanctum)
Route::middleware('auth:sanctum')->group(function () {
    Route::get('user', [UserController::class, 'profile']);
    Route::post('logout', [AuthController::class, 'logout']);

    // Cart
    Route::get('cart', [CartController::class, 'index']);
    Route::post('cart/add', [CartController::class, 'add']);
    Route::post('cart/update/{id}', [CartController::class, 'update']);
    Route::delete('cart/remove/{id}', [CartController::class, 'remove']);
    Route::post('cart/redeem/{id}', [CartController::class, 'redeem']);

    // Orders
    Route::get('orders', [OrderController::class, 'index']);
    Route::post('orders', [OrderController::class, 'store']);
    Route::get('orders/{order}', [OrderController::class, 'show']);
    Route::delete('orders/{order}', [OrderController::class, 'destroy']);

    // Packages
    Route::post('packages/buy/{id}', [PackageController::class, 'buy']);
    Route::get('packages/history', [PackageController::class, 'history']);

    // User
    Route::put('user', [UserController::class, 'update']);

    // Unified order/package history for admin
    Route::get('admin/history', [UserController::class, 'adminHistory']);

    // Admin product/package management (for admin role only, add middleware as needed)
    Route::post('products', [ProductController::class, 'store']);
    Route::put('products/{product}', [ProductController::class, 'update']);
    Route::delete('products/{product}', [ProductController::class, 'destroy']);
    Route::post('packages', [PackageController::class, 'store']);
    Route::put('packages/{package}', [PackageController::class, 'update']);
    Route::delete('packages/{package}', [PackageController::class, 'destroy']);

    // User guide feedback (optional backend)
    Route::post('feedback', [UserController::class, 'feedback']);

    // User RAG chat endpoint
    Route::post('rag/chat', [\App\Http\Controllers\Api\RagController::class, 'chat']);
});
