<?php

use App\Http\Controllers\AdminApiController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\TryOnController;
use Illuminate\Support\Facades\Route;

// ─────────────────────────────────────────────────────────────────────────────
// Session-Based Authentication Endpoints
// ─────────────────────────────────────────────────────────────────────────────
Route::prefix('auth')->group(function () {
    Route::post('register', [AuthController::class, 'register']);
    Route::post('login', [AuthController::class, 'apiLogin']);
    Route::post('logout', [AuthController::class, 'apiLogout'])->middleware('auth');
    Route::get('me', [AuthController::class, 'me'])->middleware('auth');
    Route::post('me', [AuthController::class, 'me'])->middleware('auth');
});

// ─────────────────────────────────────────────────────────────────────────────
// Public Catalog & Try-On Endpoints
// ─────────────────────────────────────────────────────────────────────────────
Route::get('products', [ProductController::class, 'index']);
Route::get('products/{identifier}', [ProductController::class, 'show']);
Route::get('categories', [CategoryController::class, 'index']);

Route::get('virtual-tryon/models', [TryOnController::class, 'getDemoModels']);
Route::post('virtual-tryon/process', [TryOnController::class, 'tryOn']);

// ─────────────────────────────────────────────────────────────────────────────
// Admin-Only REST Suite (Protected by Session auth + admin role)
// ─────────────────────────────────────────────────────────────────────────────
Route::middleware(['auth', 'admin'])->prefix('admin')->group(function () {
    // 1. Dashboard Overview Stats & KPIs
    Route::get('dashboard-stats', [AdminApiController::class, 'dashboardStats']);

    // 2. Products Catalog Management
    Route::get('products', [ProductController::class, 'index']);
    Route::post('products', [ProductController::class, 'store']);
    Route::post('products/{product}', [ProductController::class, 'update']);
    Route::delete('products/{product}', [ProductController::class, 'destroy']);

    // 3. Categories Management
    Route::get('categories', [CategoryController::class, 'index']);
    Route::post('categories', [CategoryController::class, 'store']);
    Route::delete('categories/{category}', [CategoryController::class, 'destroy']);

    // 4. Orders Processing
    Route::get('orders', [AdminApiController::class, 'getOrders']);
    Route::patch('orders/{id}/status', [AdminApiController::class, 'updateOrderStatus']);

    // 5. Customers
    Route::get('customers', [AdminApiController::class, 'getCustomers']);

    // 6. Sales Associates & Payouts
    Route::get('associates', [AdminApiController::class, 'getAssociates']);
    Route::patch('associates/{id}', [AdminApiController::class, 'updateAssociate']);
    Route::post('associates/{id}/payout', [AdminApiController::class, 'approvePayout']);

    // 7. Offers & Coupons
    Route::get('coupons', [AdminApiController::class, 'getCoupons']);
    Route::post('coupons', [AdminApiController::class, 'storeCoupon']);
    Route::patch('coupons/{id}/toggle', [AdminApiController::class, 'toggleCoupon']);
    Route::delete('coupons/{id}', [AdminApiController::class, 'deleteCoupon']);

    // 8. Reviews Moderation
    Route::get('reviews', [AdminApiController::class, 'getReviews']);
    Route::patch('reviews/{id}', [AdminApiController::class, 'updateReview']);
    Route::delete('reviews/{id}', [AdminApiController::class, 'deleteReview']);

    // 9. Analytics & Monthly Reports
    Route::get('reports/monthly', [AdminApiController::class, 'getMonthlyReports']);
});

// ─────────────────────────────────────────────────────────────────────────────
// Payment Endpoints
// ─────────────────────────────────────────────────────────────────────────────
Route::middleware('auth')->prefix('payment')->group(function () {
    Route::post('create-order', [PaymentController::class, 'createOrder']);
    Route::post('verify', [PaymentController::class, 'verify']);
    Route::get('order', [PaymentController::class, 'getOrder']);
});
