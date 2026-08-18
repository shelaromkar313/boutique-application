<?php

use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index']);

Route::get('/about', function () {
    return view('about');
});

Route::get('/contact', function () {
    return view('contact');
});

Route::get('/shop', function () {
    return view('shop');
});

Route::get('/product/{id}', function ($id) {
    return view('product', ['id' => $id]);
});

Route::get('/wishlist', function () {
    return view('wishlist');
});

Route::get('/cart', function () {
    return view('cart');
});

Route::get('/checkout', function () {
    return view('checkout');
});

Route::get('/register', function () {
    return view('register');
});

Route::get('/admin', function () {
    return view('admin-dashboard');
});

Route::get('/admin/dashboard', function () {
    return view('admin-dashboard');
});

Route::get('/login', function () {
    return view('login');
});

Route::post('/login', function () {
    return redirect('/')->with('success', 'Welcome to Estilo Wear!');
});
