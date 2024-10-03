<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group( function () {
    //Route untuk register user
    Route::post('auth/register', \App\Http\Controllers\Api\Auth\RegisterController::class);

    //Route untuk login user
    Route::post('auth/login', \App\Http\Controllers\Api\Auth\LoginController::class);

    //Route yang hanya bisa diakses dengan token
    Route::middleware('auth:sanctum')->group(function () {
        //Route untuk Logout user
        Route::post('auth/logout', \App\Http\Controllers\Api\Auth\LogoutController::class);

        //Route resources categories
        Route::resource('categories', \App\Http\Controllers\Api\CategoryController::class)->except(['edit','create']);

        //Route resources products
        Route::resource('products', \App\Http\Controllers\Api\ProductController::class)->except(['edit','create']);

        //Route resources gameplays
        // Route::resource('gameplays', \App\Http\Controllers\Api\GameplayController::class)->except(['edit','create','update']);
    });

});
