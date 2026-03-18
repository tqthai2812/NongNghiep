<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\CategoriesController;
use App\Http\Controllers\Admin\UserController;

Route::prefix('admin')->middleware(['auth', 'role.admin'])
    ->name('admin.')
    ->group(function () {
        Route::get('dashboard', [DashboardController::class, 'index'])
            ->name('dashboard');
        Route::resource('users', UserController::class);
        Route::resource('products', ProductController::class);
        Route::resource('categories', CategoriesController::class);
        Route::resource('orders', OrderController::class)->only(['index', 'show', 'destroy']);
        Route::patch('orders/{order}/status', [OrderController::class, 'updateStatus'])->name('orders.updateStatus');
        Route::get('orders/{order}/print', [OrderController::class, 'print'])->name('orders.print');
        // Route xuất báo cáo doanh thu
    });
Route::get('orders/export/revenue', [\App\Http\Controllers\Admin\OrderController::class, 'getRevenueReportData'])->name('admin.orders.report.revenue');
