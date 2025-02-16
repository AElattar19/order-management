<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\Api\Auth\AuthController;

// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');



Route::post('login', [AuthController::class, 'login']);

Route::post('register', [AuthController::class, 'register']);


Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);

    Route::get('/products/show/all', [ProductController::class, 'index']);
    Route::get('/product/show/{slug}', [ProductController::class, 'show']);

    Route::post('/AddOrder', [OrderController::class, 'store']);
    Route::get('/order/show', [OrderController::class, 'index']);
    Route::post('/order/edit/{id}', [OrderController::class, 'update']);
    Route::delete('/order/delete/{id}', [OrderController::class, 'destroy']);




});