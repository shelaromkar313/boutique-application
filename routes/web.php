<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\SalesAssociateController;
use App\Http\Controllers\TryOnController;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// ─────────────────────────────────────────────────────────────────────────────
// Public Pages & Storefront
// ─────────────────────────────────────────────────────────────────────────────
Route::get('/', [HomeController::class, 'index']);
Route::get('/shop', function () { return view('shop'); });
Route::get('/about', function () { return view('about'); });
Route::get('/contact', function () { return view('contact'); });
Route::get('/wishlist', function () { return view('wishlist'); });
Route::get('/cart', function () { return view('cart'); });

// ── Checkout - Allow both authenticated and guest customers ──
Route::get('/checkout', function () { 
    return view('checkout'); 
})->middleware('web'); // Allow guest checkout
Route::post('/checkout/place-order', [PaymentController::class, 'placeOrder'])->middleware('web'); // Allow guest orders

Route::post('/api/payments/create-order', [PaymentController::class, 'createOrder']);
Route::post('/api/payments/verify', [PaymentController::class, 'verify']);

// ─────────────────────────────────────────────────────────────────────────────
// Referral Tracking — Short Link + Session Cookie
// /ref/ESTILO-SA01          → stores ref in session, redirects to /shop
// /product/{id}?ref=CODE    → session is picked up at checkout
// ─────────────────────────────────────────────────────────────────────────────
Route::get('/ref/{code}', function (Request $request, string $code) {
    // Store the referral code in session so checkout can credit the associate
    $request->session()->put('referral_code', strtoupper($code));

    // Optionally redirect to a specific product if ?product= is given
    $productId = $request->query('product');
    if ($productId) {
        return redirect('/product/' . $productId);
    }
    return redirect('/shop')->with('info', '✨ You arrived via a verified Estilo Partner link. Enjoy curated handloom luxury!');
})->name('referral.short');

// Capture ?ref= on any product page and persist to session
Route::get('/product/{id}', function (Request $request, $id) {
    if ($request->has('ref')) {
        $request->session()->put('referral_code', strtoupper($request->query('ref')));
    }
    return view('product', ['id' => $id]);
});

// Virtual Try-On API
Route::get('/api/virtual-tryon/models', [TryOnController::class, 'getDemoModels']);
Route::post('/api/virtual-tryon/process', [TryOnController::class, 'tryOn']);

// ─────────────────────────────────────────────────────────────────────────────
// Multi-Role Authentication & Registration (Customer, Sales Associate, Admin)
// ─────────────────────────────────────────────────────────────────────────────
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/register', function () { return view('register'); });
Route::post('/register', [AuthController::class, 'registerCustomer']);
Route::get('/sales/register', function () { return view('sales.register'); });
Route::post('/sales/register', [AuthController::class, 'registerSales']);

// ── Protected Routes (Require Authentication + Cache Control) ──
Route::middleware(['auth', 'SecurePageCacheControl'])->group(function () {
    Route::get('/profile', [AuthController::class, 'showProfile'])->name('profile');
    Route::post('/profile', [AuthController::class, 'updateCustomerProfile']);
});

Route::match(['get', 'post'], '/logout', [AuthController::class, 'logout'])->name('logout');

// ─────────────────────────────────────────────────────────────────────────────
// 5. Sales Associate (Executive) Module
// ─────────────────────────────────────────────────────────────────────────────
Route::prefix('sales')->group(function () {
    Route::get('/', [SalesAssociateController::class, 'dashboard']);
    Route::get('/dashboard', [SalesAssociateController::class, 'dashboard']);
    Route::get('/earnings', [SalesAssociateController::class, 'earnings']);
    Route::post('/profile', [SalesAssociateController::class, 'updateProfile']);
    Route::post('/payout', [SalesAssociateController::class, 'requestPayout']);
});

// ─────────────────────────────────────────────────────────────────────────────
// 4. Admin Management Suite (Protected by Admin Middleware)
// ─────────────────────────────────────────────────────────────────────────────
Route::prefix('admin')->middleware('admin')->group(function () {
    Route::get('/', [AdminController::class, 'index']);
    Route::get('/dashboard', [AdminController::class, 'index']);
    Route::post('/profile', [AdminController::class, 'updateProfile']);

    // 4.3 Inventory & Products
    Route::post('/products', [AdminController::class, 'storeProduct']);
    Route::post('/products/{id}', [AdminController::class, 'updateProduct']);
    Route::delete('/products/{id}', [AdminController::class, 'deleteProduct']);
    Route::post('/categories', [AdminController::class, 'storeCategory']);
    Route::delete('/categories/{id}', [AdminController::class, 'deleteCategory']);
    Route::post('/reviews/{id}', [AdminController::class, 'updateReview']);
    Route::delete('/reviews/{id}', [AdminController::class, 'deleteReview']);

    // 4.4 Orders Processing
    Route::post('/orders/{id}/status', [AdminController::class, 'updateOrderStatus']);

    // 4.6 Marketing Associates & Payouts
    Route::post('/associates/{id}', [AdminController::class, 'updateAssociate']);
    Route::post('/associates/{id}/payout', [AdminController::class, 'approvePayout']);

    // 4.8 Offers & Coupons
    Route::post('/coupons', [AdminController::class, 'storeCoupon']);
    Route::post('/coupons/{id}/toggle', [AdminController::class, 'toggleCoupon']);
    Route::delete('/coupons/{id}', [AdminController::class, 'deleteCoupon']);
});

