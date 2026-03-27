<?php

use App\Http\Controllers\Api\ReportController as ApiReportController;
use App\Http\Middleware\ForceJsonResponse;
use Illuminate\Support\Facades\Route;

Route::middleware(['web', ForceJsonResponse::class])
    ->name('api.')
    ->prefix('v1')
    ->group(function () {
        require base_path('app/Modules/Auth/Routes/api.php');
        require base_path('app/Modules/Product/Routes/api.php');
        require base_path('app/Modules/User/Routes/api.php');
        require base_path('app/Modules/Client/Routes/api.php');
        require base_path('app/Modules/Sale/Routes/api.php');
    });

Route::middleware(['web', 'auth'])
    ->name('api.')
    ->prefix('v1')
    ->group(function () {
        Route::get('/reports/products', [ApiReportController::class, 'products'])
            ->name('reports.products');
    });
