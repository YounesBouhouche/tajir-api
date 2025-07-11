<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CodeCheckController;
use App\Http\Controllers\ForgotPasswordController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ResetPasswordController;
use Illuminate\Support\Facades\Route;


Route::prefix('v1')->group(function () {
    // Auth routes
    Route::controller(AuthController::class)->prefix('user')->group(function () {
        Route::post('register', 'register')->name('user.register');
        Route::post('login', 'login')->name('user.login');
        Route::post('reset', 'reset')->name('user.reset');
        Route::middleware(['auth:sanctum'])->get('', 'index')->name('user.index');
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
});
