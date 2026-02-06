<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ProductApiController;
use App\Http\Controllers\Api\CartApiController;

Route::prefix('v1')->group(function () {

    // Products
    Route::get('/products', [ProductApiController::class, 'index']);

    // Cart
    Route::post('/cart/add', [CartApiController::class, 'addToCart']);

    // Cart List
    Route::get('/cart', [CartApiController::class, 'cartList']);

    // Cart Update
    Route::put('/cart/update', [CartApiController::class, 'updateCartItem']);

    Route::delete('/cart/remove/{id}', [CartApiController::class, 'removeCartItem']);

    Route::post('/checkout', [CartApiController::class, 'checkout']);
});
