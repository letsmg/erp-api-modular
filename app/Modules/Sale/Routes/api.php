<?php

use App\Modules\Sale\Controllers\SaleController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth')->group(function () {
    Route::resource('sales', SaleController::class)->except(['create', 'edit']);
});
