<?php


use App\Http\Controllers\Api\Auth\PasswordController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Pos\ProductController;
use App\Http\Controllers\Api\UserController;


// protected route
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/users', [UserController::class, 'index']);
    Route::get('/products', [ProductController::class, 'index']);
    Route::delete('/products/{id}', [ProductController::class, 'destroy']);
    Route::post('/products', [ProductController::class, 'store']);
    Route::put('/products/{id}', [ProductController::class, 'update']);
    Route::get('/products/{id}', [ProductController::class, 'show']);
});

Route::middleware('guest:api')->group(function () {
    Route::post('/forgot-password', [PasswordController::class, 'forgotPassword']);
    Route::post('/reset-password', [PasswordController::class, 'resetPassword']);
});
