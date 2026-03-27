<?php

use App\Modules\Client\Controllers\ClientController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth')->group(function () {
    Route::resource('clients', ClientController::class)->except(['create', 'edit']);
});
