<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\DashboardController;

Route::prefix('admin')->middleware(['auth', 'role.admin'])
    ->name('admin.')
    ->group(function () {
        Route::get('dashboard', [DashboardController::class, 'index'])
            ->name('dashboard');
        Route::resource('users', App\Http\Controllers\Admin\UserController::class);
        Route::resource('products', App\Http\Controllers\Admin\ProductController::class);
        Route::resource('categories', App\Http\Controllers\Admin\CategoriesController::class);
    });
