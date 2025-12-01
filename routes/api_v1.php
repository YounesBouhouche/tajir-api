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
use App\Http\Controllers\ProductTypeController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::controller(AuthController::class)->prefix('auth')->group(function () {
    Route::post('register', 'register')->name('user.register');
    Route::post('login', 'login')->name('user.login');
    Route::middleware(['auth:sanctum'])->post('logout', 'logout')->name('user.logout');
});

Route::prefix('user')->middleware('auth:sanctum')->group(function () {
    // User routes
    Route::controller(UserController::class)->prefix('profile')->group(function () {
        Route::get('', 'index')->name('users.index');
        Route::patch('', 'update')->name('users.update');
        Route::get('products', 'products')->name('users.products');
    });

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

    // Cart routes
    Route::controller(CartController::class)->prefix('cart')->group(function () {
        Route::get('', 'index')->name('cart.index');
        Route::delete('', 'destroy')->name('cart.delete');
        Route::prefix('items')->group(function () {
            Route::post('', 'add')->name('cart.items.add');
            Route::put('{item}', 'edit')->name('cart.items.edit');
            Route::delete('{item}', 'delete')->name('cart.items.delete');
        });
    });

    // Order routes
    Route::controller(OrderController::class)->prefix('orders')->group(function () {
        Route::get('', 'index')->name('orders.index');
        Route::post('', 'create')->name('orders.create');
        Route::can('access', 'order')->group(function () {
            Route::get('{order}', 'show')->name('orders.show');
            Route::put('{order}', 'update')->name('orders.update');
            Route::patch('{order}', 'place')->name('orders.place');
        });
    });

    // Shipping address routes
    Route::middleware(['auth:sanctum'])->apiResource('address', ShippingAddressController::class);

    // Rating routes
    Route::controller(RatingController::class)->prefix('ratings')->group(function () {
        Route::get('', 'index')->name('rating.index');
        Route::get('{id}', 'show')->name('rating.show');
        Route::post('', 'store')->name('rating.store');
        Route::put('{id}', 'update')->name('rating.update');
        Route::delete('{id}', 'destroy')->name('rating.delete');
    });
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

Route::apiResource('categories', CategoryController::class, [
    'names' => [
        'index' => 'categories.index',
        'show' => 'categories.show',
    ],
    'only' => [
        'index',
        'show',
    ],
]);

Route::controller(ProductTypeController::class)->prefix('product-types')->group(function () {
    Route::get('', 'index')->name('product-types.index');
    Route::get('{productType}', 'show')->name('product-types.show');
});
