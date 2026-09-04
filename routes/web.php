<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\SalesAssociateController;
use App\Http\Controllers\TryOnController;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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

Route::post('/api/payments/create-order', [PaymentController::class, 'createOrder']);
Route::post('/api/payments/verify', [PaymentController::class, 'verify']);
Route::post('/api/coupons/validate', [PaymentController::class, 'validateCoupon']);

// ─────────────────────────────────────────────────────────────────────────────
// Referral Tracking — Short Link + Session Cookie
// ─────────────────────────────────────────────────────────────────────────────
Route::get('/ref/{code}', function (Request $request, string $code) {
    $request->session()->put('referral_code', strtoupper($code));
    $productId = $request->query('product');
    if ($productId) {
        return redirect('/product/' . $productId);
    }
    return redirect('/shop')->with('info', '✨ You arrived via a verified Estilo Partner link. Enjoy curated handloom luxury!');
})->name('referral.short');

// Capture ?ref= on product page
Route::get('/product/{id}', function (Request $request, $id) {
    if ($request->has('ref')) {
        $request->session()->put('referral_code', strtoupper($request->query('ref')));
    }
    return view('product', ['id' => $id]);
});

// Customer Ratings & Reviews
Route::post('/product/{id}/review', [\App\Http\Controllers\ReviewController::class, 'store'])->name('product.review.store');
Route::get('/api/product/{id}/reviews', [\App\Http\Controllers\ReviewController::class, 'index']);

// Virtual Try-On API
Route::get('/api/virtual-tryon/models', [TryOnController::class, 'getDemoModels']);
Route::post('/api/virtual-tryon/process', [TryOnController::class, 'tryOn']);

// ─────────────────────────────────────────────────────────────────────────────
// Multi-Role Authentication & Registration
// ─────────────────────────────────────────────────────────────────────────────
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->middleware('throttle:10,1');
Route::get('/register', function () { return view('register'); });
Route::post('/register', [AuthController::class, 'registerCustomer']);
Route::get('/sales/register', function () { return view('sales.register'); });
Route::post('/sales/register', [AuthController::class, 'registerSales']);

// ── Protected Routes (Require Authentication) ──
Route::middleware(['auth', 'SecurePageCacheControl'])->group(function () {
    Route::get('/checkout', function () { 
        return view('checkout'); 
    });
    Route::post('/checkout/place-order', [PaymentController::class, 'placeOrder']);
    Route::get('/profile', [AuthController::class, 'showProfile'])->name('profile');
    Route::post('/profile', [AuthController::class, 'updateCustomerProfile']);
    Route::post('/orders/{id}/return-request', [PaymentController::class, 'requestReturn'])->name('orders.return');
});

Route::match(['get', 'post'], '/logout', [AuthController::class, 'logout'])->name('logout');

// ─────────────────────────────────────────────────────────────────────────────
// Sales Associate Module
// ─────────────────────────────────────────────────────────────────────────────
Route::prefix('sales')->group(function () {
    Route::get('/', [SalesAssociateController::class, 'dashboard']);
    Route::get('/dashboard', [SalesAssociateController::class, 'dashboard']);
    Route::get('/earnings', [SalesAssociateController::class, 'earnings']);
    Route::post('/profile', [SalesAssociateController::class, 'updateProfile']);
    Route::post('/payout', [SalesAssociateController::class, 'requestPayout']);
});

// ─────────────────────────────────────────────────────────────────────────────
// Admin Authentication & Management Suite
// ─────────────────────────────────────────────────────────────────────────────
Route::get('/estilo-hq-console/login', [AuthController::class, 'showAdminLogin'])->name('admin.login');
Route::post('/estilo-hq-console/login', [AuthController::class, 'adminLogin'])->middleware('throttle:10,1');
Route::match(['get', 'post'], '/estilo-hq-console/logout', [AuthController::class, 'adminLogout'])->name('admin.logout');

$adminRoutes = function () {
    Route::get('/', [AdminController::class, 'index']);
    Route::get('/dashboard', [AdminController::class, 'index']);
    Route::get('/profile', [AdminController::class, 'profile']);
    Route::post('/profile', [AdminController::class, 'updateProfile']);

    Route::post('/products', [AdminController::class, 'storeProduct']);
    Route::match(['post', 'put'], '/products/{id}', [AdminController::class, 'updateProduct']);
    Route::delete('/products/{id}', [AdminController::class, 'deleteProduct']);
    Route::post('/categories', [AdminController::class, 'storeCategory']);
    Route::delete('/categories/{id}', [AdminController::class, 'deleteCategory']);
    Route::delete('/fabrics', [AdminController::class, 'deleteFabric']);

    Route::post('/categories/{id}/photo', [AdminController::class, 'updateCategoryPhoto']);

    Route::post('/hero-slides', [AdminController::class, 'storeHeroSlide']);
    Route::post('/hero-slides/{id}', [AdminController::class, 'updateHeroSlide']);
    Route::delete('/hero-slides/{id}', [AdminController::class, 'deleteHeroSlide']);

    Route::post('/occasions', [AdminController::class, 'storeHomeOccasion']);
    Route::post('/occasions/{id}', [AdminController::class, 'updateHomeOccasion']);
    Route::delete('/occasions/{id}', [AdminController::class, 'deleteHomeOccasion']);

    Route::match(['get', 'post'], '/reviews/{id}/boost', [AdminController::class, 'boostReview']);
    Route::match(['get', 'post'], '/reviews/{id}/toggle', [AdminController::class, 'toggleReview']);
    Route::post('/reviews/{id}', [AdminController::class, 'updateReview']);
    Route::delete('/reviews/{id}', [AdminController::class, 'deleteReview']);
    Route::match(['get', 'post', 'delete'], '/reviews/{id}/delete', [AdminController::class, 'deleteReview']);

    Route::post('/orders/{id}/status', [AdminController::class, 'updateOrderStatus']);

    Route::post('/associates/{id}', [AdminController::class, 'updateAssociate']);
    Route::post('/associates/{id}/payout', [AdminController::class, 'approvePayout']);

    Route::get('/products/create', [AdminController::class, 'createProduct']);
    Route::get('/coupons/create', [AdminController::class, 'createCoupon']);
    Route::get('/announcements/create', [AdminController::class, 'createAnnouncement']);

    Route::post('/coupons', [AdminController::class, 'storeCoupon']);
    Route::post('/coupons/{id}', [AdminController::class, 'updateCoupon']);
    Route::post('/coupons/{id}/toggle', [AdminController::class, 'toggleCoupon']);
    Route::post('/coupons/{id}/announce', [AdminController::class, 'announceCoupon']);
    Route::delete('/coupons/{id}', [AdminController::class, 'deleteCoupon']);

    Route::post('/announcements', [AdminController::class, 'storeAnnouncement']);
    Route::post('/announcements/{id}', [AdminController::class, 'updateAnnouncement']);
    Route::post('/announcements/{id}/toggle', [AdminController::class, 'toggleAnnouncement']);
    Route::delete('/announcements/{id}', [AdminController::class, 'deleteAnnouncement']);
};

Route::get('/estilo-hq-console', function () {
    if (Auth::check() && optional(Auth::user())->role === 'admin') {
        return redirect('/estilo-hq-console/dashboard');
    }

    return redirect('/estilo-hq-console/login');
});

Route::prefix('estilo-hq-console')->middleware('admin')->group($adminRoutes);
Route::prefix('admin')->middleware('admin')->group($adminRoutes);
