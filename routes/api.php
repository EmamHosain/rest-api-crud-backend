<?php

use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\BrandController;
use App\Http\Controllers\CategoryController;
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



    Route::post('/categories', [CategoryController::class, 'create']);
    Route::get('/categories', [CategoryController::class, 'index']);
    Route::get('/categories/{id}', [CategoryController::class, 'show']);
    Route::put('/categories/{id}', [CategoryController::class, 'update']);
    Route::delete('/categories/{id}', [CategoryController::class, 'destroy']);


    Route::post('/brands', [BrandController::class, 'create']);
    Route::get('/brands', [BrandController::class, 'index']);
    Route::get('/brands/{id}', [BrandController::class, 'show']);
    Route::put('/brands/{id}', [BrandController::class, 'update']);
    Route::delete('/brands/{id}', [BrandController::class, 'destroy']);



    Route::post('/products', [CategoryController::class, 'create']);
    Route::get('/products', [CategoryController::class, 'index']);
    Route::get('/products/{id}', [CategoryController::class, 'show']);
    Route::put('/products/{id}', [CategoryController::class, 'update']);
    Route::delete('/products/{id}', [CategoryController::class, 'destroy']);



});
