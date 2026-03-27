<?php

use App\Modules\User\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth')->group(function () {
    Route::patch('/users/{user}/reset-password', [UserController::class, 'resetPassword'])
        ->name('users.reset');
    Route::patch('/users/{user}/toggle', [UserController::class, 'toggleStatus'])
        ->name('users.toggle');
    Route::resource('users', UserController::class)->except(['create', 'edit']);
});
