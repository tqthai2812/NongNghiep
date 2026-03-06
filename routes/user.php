<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\User\Homepage;
use App\Http\Controllers\User\CartController;
use App\Http\Controllers\User\ProfileController;

Route::prefix('user')
    ->name('user.')
    ->group(function () {
        Route::get('homepage', [Homepage::class, 'index'])->name('homepage');
        Route::get('product/{id}', [Homepage::class, 'show'])->name('product.show');
        Route::get('test', [Homepage::class, 'test'])->name('test');
    });
Route::get('cart', [CartController::class, 'index'])->name('cart.index');

Route::prefix('user')
    ->name('user.')
    ->group(function () {
        Route::get('profile', [ProfileController::class, 'index'])->name('profile');
        Route::get('order_history', [ProfileController::class, 'orderhistory'])->name('order_history');
    });
