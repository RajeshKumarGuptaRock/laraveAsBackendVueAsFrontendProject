<?php

namespace App\Http\Controllers\Api\V2\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Traits\ApiResponseTrait;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use App\Http\Requests\Auth\ResetPasswordRequest;
use Illuminate\Support\Facades\Log;

class ResetPasswordController extends Controller
{
    use ApiResponseTrait;

    public function __invoke(ResetPasswordRequest $request): JsonResponse
    {
        try {
            $status = Password::reset(

                $request->only(
                    'email',
                    'password',
                    'password_confirmation',
                    'token'
                ),

                function (User $user, string $password) {

                    $user->forceFill([
                        'password' => Hash::make($password),
                        'remember_token' => Str::random(60),
                    ])->save();

                    $user->tokens()->delete();

                    event(new PasswordReset($user));
                }
            );

            if ($status === Password::PASSWORD_RESET) {

                return $this->successResponse(
                    null,
                    'Password reset successfully.'
                );
            }

            return $this->errorResponse(
                __($status),
                null,
                422
            );
        } catch (\Throwable $exception) {
            Log::error('Forgot Password Error', [
                'message' => $exception->getMessage(),
            ]);

            return $this->errorResponse(
                'Unable to process forgot password request.',
                null,
                500
            );
        }
    }
}
