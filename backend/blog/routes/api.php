<?php

use App\Http\Controllers\Api\Auth\ResendVerificationEmailController;
use App\Http\Controllers\Api\Auth\VerifyEmailController;
use App\Http\Resources\UserResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\Auth\LoginController;
use App\Http\Controllers\Api\V1\Auth\LogoutController;
use App\Http\Controllers\Api\V1\Auth\RegisterController;
use App\Http\Controllers\Api\V1\Auth\UserController;

// Route::post('/register', RegisterController::class);
// Route::post('/login', LoginController::class);
// Route::middleware('auth:sanctum')->post('/logout', LogoutController::class);

// Route::middleware('auth:sanctum')->group(function () {
//     Route::get('/userList', UserController::class);
// });
Route::prefix('v1')
    ->middleware('api')
    ->group(base_path('routes/api/v1.php'));

Route::prefix('v2')
    ->middleware('api')
    ->group(base_path('routes/api/v2.php'));
