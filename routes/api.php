<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ProductController;
use Illuminate\Support\Facades\Route;

Route::group([
    'middleware' => 'api',
    'prefix' => 'auth',
], function ($router) {
    Route::post('register', [AuthController::class, 'register']);
    Route::post('login', [AuthController::class, 'login']);
    Route::post('logout', [AuthController::class, 'logout'])->middleware('auth:api');
    Route::post('refresh', [AuthController::class, 'refresh'])->middleware('auth:api');
    Route::post('me', [AuthController::class, 'me'])->middleware('auth:api');
});

// Public catalog endpoints
Route::get('products', [ProductController::class, 'index']);
Route::get('products/{identifier}', [ProductController::class, 'show']);
Route::get('categories', [CategoryController::class, 'index']);

// Admin-only endpoints
Route::middleware(['auth:api', 'admin'])->prefix('admin')->group(function () {
    Route::get('products', [ProductController::class, 'index']);
    Route::post('products', [ProductController::class, 'store']);
    Route::post('products/{product}', [ProductController::class, 'update']);
    Route::delete('products/{product}', [ProductController::class, 'destroy']);
    Route::post('categories', [CategoryController::class, 'store']);
    Route::delete('categories/{category}', [CategoryController::class, 'destroy']);
});

// Payment endpoints (accessible by all authenticated users)
Route::middleware('auth:api')->prefix('payment')->group(function () {
    Route::post('create-order', [PaymentController::class, 'createOrder']);
    Route::post('verify', [PaymentController::class, 'verify']);
    Route::get('order', [PaymentController::class, 'getOrder']);
});
