<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CodeCheckController;
use App\Http\Controllers\ForgotPasswordController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ResetPasswordController;
use App\Http\Resources\OrderCollection;
use Illuminate\Support\Facades\Route;


Route::prefix('v1')->group(function () {
    // Auth routes
    Route::controller(AuthController::class)->prefix('user')->group(function () {
        Route::post('register', 'register')->name('user.register');
        Route::post('login', 'login')->name('user.login');
        Route::middleware(['auth:sanctum'])->group(function () {
            Route::get('', 'index')->name('user.index');
            Route::patch('', 'update')->name('user.update');
            Route::delete('', 'destroy')->name('user.delete');
        });
    });

    Route::prefix('password')->group(function () {
        Route::post('send', ForgotPasswordController::class);
        Route::post('check', CodeCheckController::class);
        Route::post('reset', ResetPasswordController::class);
    });

    // Product routes
    Route::controller(ProductController::class)->prefix('products')->group(function () {
        Route::get('', 'index')->name('products.index');
        Route::get('{id}', 'show')->name('products.show');
        Route::middleware(['auth:sanctum'])->post('', 'store')->name('products.store');
        Route::patch('{product}/update', 'update')->name('products.update');
        Route::middleware(['auth:sanctum'])->delete('{product}', 'destroy')->name('products.delete');
    });

    Route::controller(CartController::class)->middleware(['auth:sanctum'])->prefix('cart')->group(function () {
        Route::get('', 'index')->name('cart.index');
        Route::post('add', 'add')->name('cart.create');
        Route::post('remove', 'remove')->name('cart.remove');
        Route::delete('', 'destroy')->name('cart.delete');
        Route::post('checkout', 'checkout')->name('cart.checkout');
    });

    Route::controller(OrderController::class)->middleware(['auth:sanctum'])->prefix('orders')->group(function () {
        Route::get('', 'index')->name('orders.index');
        Route::get('{order}', 'show')->name('orders.show');
        Route::patch('{order}', 'update')->name('orders.update');
    });
});
