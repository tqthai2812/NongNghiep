<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\User\Homepage;
use App\Http\Controllers\User\CartController;
use App\Http\Controllers\User\ProfileController;
use App\Http\Controllers\User\CheckoutController;
use App\Http\Controllers\User\AddressController;
use App\Http\Controllers\User\ChatController;

Route::name('user.')
    ->group(function () {
        Route::get('/', [Homepage::class, 'index'])->name('homepage');
        Route::get('product/{id}', [Homepage::class, 'productDetail'])->name('product.detail');
    });
Route::get('cart', [CartController::class, 'index'])->name('cart.index');

Route::prefix('user')
    ->name('user.')
    ->group(function () {
        Route::get('profile', [ProfileController::class, 'index'])->name('profile');
        Route::get('order_history', [ProfileController::class, 'orderhistory'])->name('order_history');
    });

Route::middleware(['auth'])->prefix('user')->name('user.')->group(function () {
    // ... các route cũ
    Route::post('cart/add', [CartController::class, 'addToCart'])->name('cart.add');
});

Route::middleware(['auth'])->prefix('cart')->name('cart.')->group(function () {
    Route::get('/', [CartController::class, 'index'])->name('index');
    Route::post('/update', [CartController::class, 'updateQuantity'])->name('update');
    Route::delete('/delete/{id}', [CartController::class, 'destroy'])->name('delete');
});

Route::middleware(['auth'])->group(function () {
    Route::match(['GET', 'POST'], '/checkout', [CheckoutController::class, 'index'])->name('checkout.index');
});

// Nhóm route yêu cầu user phải đăng nhập
Route::middleware(['auth'])->group(function () {
    Route::post('/user/addresses', [AddressController::class, 'store'])->name('address.store');
});

Route::put('/user/addresses/{id}', [App\Http\Controllers\User\AddressController::class, 'update'])->name('address.update');

Route::post('place-order', [App\Http\Controllers\User\CheckoutController::class, 'placeOrder'])->name('checkout.place_order');
Route::delete('/user/addresses/{id}', [App\Http\Controllers\User\AddressController::class, 'destroy'])->name('address.destroy');
Route::put('/order/{id}/cancel', [App\Http\Controllers\User\ProfileController::class, 'cancelOrder'])->name('order.cancel');

Route::post('/review/submit', [App\Http\Controllers\User\ProfileController::class, 'submitReview'])->name('review.submit');
Route::get('/user/profile', [App\Http\Controllers\User\ProfileController::class, 'profile_edit'])->name('user.profile');
Route::put('/user/profile/update', [App\Http\Controllers\User\ProfileController::class, 'updateProfile'])->name('user.profile.update');
Route::get('/user/addresses', [App\Http\Controllers\User\ProfileController::class, 'address_edit'])->name('user.address');

Route::get('/search', [App\Http\Controllers\User\SearchController::class, 'search'])->name('search');

Route::post('/chat-tu-van', [ChatController::class, 'ask'])->name('chat.ask');
