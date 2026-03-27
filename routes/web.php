<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\SitemapController;
use App\Http\Controllers\StoreController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/sitemap.xml', [SitemapController::class, 'index'])->name('sitemap');
Route::get('/', [StoreController::class, 'index'])->name('store.index');
Route::get('/store/product/{product:slug}', [StoreController::class, 'show'])->name('store.product');
Route::post('/terms/accept', [StoreController::class, 'acceptTerms'])->name('store.terms.accept');

Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'showLogin'])->name('login');
    Route::post('/login', [LoginController::class, 'login'])->name('login.post');
    Route::get('/forgot-password', [LoginController::class, 'showForgotPassword'])->name('password.request');
    Route::post('/forgot-password', [LoginController::class, 'sendResetLinkEmail'])->name('password.email.web');
});

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', fn () => Inertia::render('Dashboard'))->name('dashboard');
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

    Route::get('/products/{product:id}/preview', [ProductController::class, 'preview'])->name('products.preview');
    Route::patch('/products/{product:id}/toggle-featured', [ProductController::class, 'toggleFeatured'])->name('products.toggle-featured');
    Route::patch('/products/{product:id}/toggle', [ProductController::class, 'toggle'])->name('products.toggle');
    Route::resource('products', ProductController::class);

    Route::resource('suppliers', SupplierController::class);

    Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
    Route::get('/reports/products', [ReportController::class, 'products'])->name('reports.products');

    Route::resource('users', UserController::class);
    Route::patch('/users/{user}/reset-password', [UserController::class, 'resetPassword'])->name('users.reset');
    Route::patch('/users/{user}/toggle', [UserController::class, 'toggleStatus'])->name('users.toggle');
});
