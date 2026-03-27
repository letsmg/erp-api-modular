<?php

use App\Modules\Product\Controllers\CategoryController;
use App\Modules\Product\Controllers\ProductController;
use App\Modules\Product\Controllers\PublicCatalogController;
use App\Modules\Product\Controllers\SupplierController;
use Illuminate\Support\Facades\Route;

Route::get('/catalog/home', [PublicCatalogController::class, 'home'])->name('catalog.home');
Route::get('/catalog/products', [PublicCatalogController::class, 'index'])->name('catalog.products.index');
Route::get('/catalog/products/{product:slug}', [PublicCatalogController::class, 'show'])->name('catalog.products.show');

Route::middleware('auth')->group(function () {
    Route::get('/products/form-options', [ProductController::class, 'formOptions'])
        ->name('products.form-options');
    Route::patch('/products/{product:id}/toggle-featured', [ProductController::class, 'toggleFeatured'])
        ->name('products.toggle-featured');
    Route::patch('/products/{product:id}/toggle', [ProductController::class, 'toggle'])
        ->name('products.toggle');
    Route::resource('products', ProductController::class)->except(['create', 'edit']);
    Route::resource('categories', CategoryController::class)->except(['create', 'edit']);
    Route::resource('suppliers', SupplierController::class)->except(['create', 'edit']);
});


