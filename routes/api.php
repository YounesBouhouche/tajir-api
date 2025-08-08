<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CodeCheckController;
use App\Http\Controllers\FavoriteProductsController;
use App\Http\Controllers\ForgotPasswordController;
use App\Http\Controllers\HistoryController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\RatingController;
use App\Http\Controllers\ResetPasswordController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\ShippingAddressController;
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
            Route::post('logout', 'logout')->name('user.logout');
        });
    });

    Route::controller(FavoriteProductsController::class)->prefix('favorite')
        ->middleware('auth:sanctum')
        ->group(function () {
            Route::get('', 'index')->name('favorites.index');
            Route::post('', 'store')->name('favorites.store');
        });

    Route::controller(HistoryController::class)->prefix('history')
        ->middleware('auth:sanctum')
        ->group(function () {
            Route::get('', 'index')->name('history.index');
            Route::post('', 'store')->name('history.store');
            Route::delete('', 'clear')->name('history.clear');
        });

    Route::middleware(['auth:sanctum'])->resource('shipping_address', ShippingAddressController::class);

    Route::prefix('password')->group(function () {
        Route::post('send', ForgotPasswordController::class);
        Route::post('check', CodeCheckController::class);
        Route::post('reset', ResetPasswordController::class);
    });

    Route::get('search', SearchController::class)->name('search');

    // Product routes
    Route::controller(ProductController::class)->prefix('products')->group(function () {
        Route::middleware(['auth:sanctum'])->group(function () {
            Route::get('{category}/recommended/auth', 'recommended')
                ->name('products.recommended');

            Route::get('{category}/{type}/auth', 'index')
                ->whereIn('type', ['popular', 'newest'])
                ->name('products.index');

            Route::post('', 'store')->name('products.store');
            Route::get('{id}/auth', 'show')->name('products.show');
        });

        Route::get('{category}/{type}', 'index')
            ->whereIn('type', ['popular', 'newest'])
            ->name('products.index');

        Route::get('{id}', 'show')->name('products.show');
        Route::patch('{product}/update', 'update')->name('products.update');
        Route::middleware(['auth:sanctum'])->delete('{product}', 'destroy')->name('products.delete');
    });

    Route::controller(CartController::class)->middleware(['auth:sanctum'])->prefix('cart')->group(function () {
        Route::get('', 'index')->name('cart.index');
        Route::post('add', 'add')->name('cart.add');
        Route::post('remove', 'remove')->name('cart.remove');
        Route::post('edit', 'edit')->name('cart.edit');
        Route::delete('', 'destroy')->name('cart.delete');
        Route::post('checkout', 'checkout')->name('cart.checkout');
    });

    Route::controller(OrderController::class)->middleware(['auth:sanctum'])->prefix('orders')->group(function () {
        Route::get('', 'index')->name('orders.index');
        Route::get('{order}', 'show')->name('orders.show');
        Route::patch('{order}', 'update')->name('orders.update');
        Route::post('{order}/place', 'place')->name('orders.place');
    });

    Route::controller(RatingController::class)->prefix('rating')->group(function () {
        Route::middleware('auth:sanctum')->group(function () {
            Route::get('', 'index')->name('rating.index');
            Route::get('{id}', 'show')->name('rating.show');
            Route::post('', 'store')->name('rating.store');
            Route::patch('{id}', 'update')->name('rating.update');
            Route::delete('{id}', 'destroy')->name('rating.delete');
        });
    });
});
