<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Api\V2\Auth\LoginController;
use App\Http\Controllers\Api\V2\Auth\RegisterController;
use App\Http\Controllers\Api\V2\Auth\LogoutController;

use App\Http\Controllers\Api\V2\Auth\UserController;

Route::prefix('auth')->group(function () {

    Route::post('/register', RegisterController::class);
    Route::post('/login', LoginController::class);

    Route::middleware('auth:sanctum')->group(function () {

        Route::post('/logout', LogoutController::class);
    });
});

Route::middleware('auth:sanctum')->group(function () {

    Route::get('/userList', UserController::class);
});
