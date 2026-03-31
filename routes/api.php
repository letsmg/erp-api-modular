<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ReportController as ApiReportController;
use App\Http\Middleware\ForceJsonResponse;
use Illuminate\Support\Facades\Route;

// Rotas públicas de autenticação API
Route::middleware([ForceJsonResponse::class])
    ->name('api.')
    ->prefix('v1')
    ->group(function () {
        Route::post('/auth/login', [\App\Http\Controllers\Api\AuthController::class, 'login'])->name('auth.login');
        
        // Rotas de catálogo público
        require base_path('app/Modules/Product/Routes/api.php');
    });

// Rotas autenticadas com Sanctum
Route::middleware(['auth:sanctum', ForceJsonResponse::class])
    ->name('api.')
    ->prefix('v1')
    ->group(function () {
        Route::post('/auth/logout', [\App\Http\Controllers\Api\AuthController::class, 'logout'])->name('auth.logout');
        Route::post('/auth/logout-all', [\App\Http\Controllers\Api\AuthController::class, 'logoutAll'])->name('auth.logout.all');
        Route::get('/auth/me', [\App\Http\Controllers\Api\AuthController::class, 'me'])->name('auth.me');
        Route::get('/auth/tokens', [\App\Http\Controllers\Api\AuthController::class, 'tokens'])->name('auth.tokens');
        Route::delete('/auth/tokens/{tokenId}', [\App\Http\Controllers\Api\AuthController::class, 'revokeToken'])->name('auth.tokens.revoke');
        
        // Rotas de módulos existentes (agora com Sanctum) - exceto catálogo
        require base_path('app/Modules/User/Routes/api.php');
        require base_path('app/Modules/Client/Routes/api.php');
        require base_path('app/Modules/Sale/Routes/api.php');
        
        // Apenas rotas protegidas de produtos
        Route::get('/products/form-options', [\App\Modules\Product\Controllers\ProductController::class, 'formOptions']);
        Route::patch('/products/{product:id}/toggle-featured', [\App\Modules\Product\Controllers\ProductController::class, 'toggleFeatured']);
        Route::patch('/products/{product:id}/toggle', [\App\Modules\Product\Controllers\ProductController::class, 'toggle']);
        Route::resource('products', \App\Modules\Product\Controllers\ProductController::class)->except(['create', 'edit']);
        Route::resource('categories', \App\Modules\Product\Controllers\CategoryController::class)->except(['create', 'edit']);
        Route::resource('suppliers', \App\Modules\Product\Controllers\SupplierController::class)->except(['create', 'edit']);
    });

// Rotas web antigas (mantidas por compatibilidade)
Route::middleware(['web', ForceJsonResponse::class])
    ->name('api.')
    ->prefix('v1')
    ->group(function () {
        Route::get('/reports/products', [ApiReportController::class, 'products'])
            ->name('reports.products');
    });
