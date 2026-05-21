<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Api\V2\Auth\LoginController;
use App\Http\Controllers\Api\V2\Auth\LogoutController;
use App\Http\Controllers\Api\V2\Auth\RegisterController;
use App\Http\Controllers\Api\V2\Auth\UserController;
use App\Http\Controllers\Api\V2\Auth\ResendVerificationController;
use App\Http\Controllers\Api\V2\Auth\ForgotPasswordController;
use App\Http\Controllers\Api\V2\Auth\VerifyEmailController;
use App\Http\Controllers\Api\V2\Auth\ResetPasswordController;

Route::prefix('auth')->group(function () {

    Route::post('/register', RegisterController::class)->middleware('throttle:5,1');
    Route::post('/login', LoginController::class)->middleware('throttle:5,1');

    Route::middleware('auth:sanctum')->group(function () {
        Route::post('/logout', LogoutController::class);
        Route::get('/userList', UserController::class)->middleware('throttle:5,1');
    });

    Route::post('/email/resend', ResendVerificationController::class)->middleware('throttle:5,1');
    Route::get('/email/verify/{id}/{hash}', VerifyEmailController::class)->middleware('throttle:5,1')->name('verification.verify');

    Route::post('/forgot-password', ForgotPasswordController::class)->middleware('throttle:5,1');
    Route::post('/reset-password', ResetPasswordController::class)->middleware('throttle:5,1');
});
