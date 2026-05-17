<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Api\V1\Auth\LoginController;
use App\Http\Controllers\Api\V1\Auth\LogoutController;
use App\Http\Controllers\Api\V1\Auth\RegisterController;
use App\Http\Controllers\Api\V1\Auth\UserController;

//use App\Http\Controllers\Api\V1\Auth\ForgotPasswordController;
use App\Http\Controllers\Api\V1\Auth\ResendVerificationController;
//use App\Http\Controllers\Api\V1\Auth\ForgotPasswordController;
//use App\Http\Controllers\Api\V1\Auth\ForgotPasswordController;
use App\Http\Controllers\Api\V1\Auth\ForgotPasswordController;
use App\Http\Controllers\Api\V1\Auth\VerifyEmailController;
use App\Http\Controllers\Api\V1\Auth\ResetPasswordController;

Route::prefix('auth')->group(function () {

    Route::post('/register', RegisterController::class);
    Route::post('/login', LoginController::class);

    Route::middleware('auth:sanctum')->group(function () {

        Route::post('/logout', LogoutController::class);

        Route::post('/email/resend', ResendVerificationController::class)->middleware('throttle:5,1');
        Route::get('/email/verify/{id}/{hash}', VerifyEmailController::class)->middleware(['auth:sanctum', 'signed']);

        Route::get('/userList', UserController::class);
    });

    Route::post('/forgot-password', ForgotPasswordController::class);

    Route::post('/reset-password', ResetPasswordController::class);
});

Route::middleware('auth:sanctum')->group(function () {});
