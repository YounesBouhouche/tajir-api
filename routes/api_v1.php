<?php

use App\Http\Controllers\Api\V1\Auth\HistoryController;
use App\Http\Controllers\Api\V1\AuthController;
use App\Http\Controllers\Api\V1\CartController;
use App\Http\Controllers\Api\V1\CategoryController;
use App\Http\Controllers\Api\V1\CodeCheckController;
use App\Http\Controllers\Api\V1\ForgotPasswordController;
use App\Http\Controllers\Api\V1\OrderController;
use App\Http\Controllers\Api\V1\ProductController;
use App\Http\Controllers\Api\V1\RatingController;
use App\Http\Controllers\Api\V1\ResetPasswordController;
use App\Http\Controllers\Api\V1\ShippingAddressController;
use App\Http\Controllers\FavoriteProductsController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::controller(AuthController::class)->prefix('auth')->group(function () {
    Route::post('register', 'register')->name('user.register');
    Route::post('login', 'login')->name('user.login');
    Route::middleware(['auth:sanctum'])->post('logout', 'logout')->name('user.logout');
});

Route::controller(UserController::class)->prefix('user')->middleware('auth:sanctum')->group(function () {
    // User routes
    Route::get('', 'index')->name('users.index');
    Route::patch('', 'update')->name('users.update');
    Route::get('products', 'products')->name('users.products');

    // Favorite products routes
    Route::resource('favorites', FavoriteProductsController::class, [
        'names' => [
            'index' => 'favorites.index',
            'store' => 'favorites.store',
            'destroy' => 'favorites.destroy',
        ],
        'only' => [
            'index',
            'store',
            'destroy',
        ],
    ]);

    // History routes
    Route::apiResource('history', HistoryController::class, [
        'names' => [
            'index' => 'history.index',
            'store' => 'history.store',
            'destroy' => 'history.destroy',
        ],
        'except' => [
            'update',
            'edit',
            'show'
        ],
    ]);

    // Shipping address routes
    Route::middleware(['auth:sanctum'])->apiResource('address', ShippingAddressController::class);
});

Route::prefix('password')->group(function () {
    Route::post('send', ForgotPasswordController::class);
    Route::post('check', CodeCheckController::class);
    Route::post('reset', ResetPasswordController::class);
});

// Product routes
Route::controller(ProductController::class)->prefix('products')->group(function () {
    Route::middleware(['auth:sanctum'])->group(function () {
        Route::post('', 'store')->name('products.store');
    });
    Route::get('', 'index')->name('products.index');
    Route::get('{product}', 'show')->name('products.show')->whereNumber('id');
    Route::put('{product}', 'update')->name('products.update');
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
    Route::can('access', 'order')->group(function () {
        Route::get('{order}', 'show')->name('orders.show');
        Route::put('{order}', 'update')->name('orders.update');
        Route::post('{order}/place', 'place')->name('orders.place');
    });
});

Route::controller(RatingController::class)->prefix('rating')
    ->middleware('auth:sanctum')->group(function () {
        Route::get('', 'index')->name('rating.index');
        Route::get('{id}', 'show')->name('rating.show');
        Route::post('', 'store')->name('rating.store');
        Route::patch('{id}', 'update')->name('rating.update');
        Route::delete('{id}', 'destroy')->name('rating.delete');
    });

Route::controller(CategoryController::class)->prefix('category')->group(function () {
    Route::get('', 'index')->name('categories');
    Route::get('{id}', 'show')->name('category');
});
