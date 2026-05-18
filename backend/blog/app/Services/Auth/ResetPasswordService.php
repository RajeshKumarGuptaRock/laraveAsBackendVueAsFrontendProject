<?php

namespace App\Services\Auth;

use App\Models\User;
use App\Traits\ApiResponseTrait;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;

class ResetPasswordService
{
    use ApiResponseTrait;

    public function resetPassword(array $data): JsonResponse
    {
        try {

            $status = Password::reset(

                [
                    'email' => $data['email'],
                    'password' => $data['password'],
                    //'password_confirmation' => $data['password_confirmation'],
                    'token' => $data['token'],
                ],

                function (User $user, string $password) {

                    $user->forceFill([
                        'password' => Hash::make($password),
                        'remember_token' => Str::random(60),
                    ])->save();

                    // Logout from all devices
                    $user->tokens()->delete();

                    event(new PasswordReset($user));
                }
            );

            if ($status === Password::PASSWORD_RESET) {

                return $this->successResponse(
                    null,
                    __('Password reset successfully.')
                );
            }

            return $this->errorResponse(
                __($status),
                null,
                422
            );
        } catch (\Throwable $exception) {

            Log::error('Reset Password Error', [
                'message' => $exception->getMessage(),
                'file' => $exception->getFile(),
                'line' => $exception->getLine(),
            ]);

            return $this->errorResponse(
                __('Unable to process password reset request.'),
                null,
                500
            );
        }
    }
}
