<?php

use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\BrandController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ProductController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;




// public route
Route::post('/register', [UserController::class, 'store']);
Route::post('/login', [UserController::class, 'login']);


Route::put('/forget-password', [UserController::class, 'forgetPassword']);
Route::post('/email-verify', [UserController::class, 'emailVerify']);
Route::post('/reset-password', [UserController::class, 'resetPassword']);

// protected route
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/users', [UserController::class, 'index']);
    Route::post('/logout', [UserController::class, 'logout']);

    // product route start here
    Route::get('/products', [ProductController::class, 'index']);
    Route::delete('/products/{id}', [ProductController::class, 'destroy']);
    Route::post('/products', [ProductController::class, 'store']);



});
