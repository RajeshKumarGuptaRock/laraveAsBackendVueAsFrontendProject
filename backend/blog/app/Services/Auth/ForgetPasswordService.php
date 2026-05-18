<?php

namespace App\Services\Auth;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Traits\ApiResponseTrait;
use App\Models\User;

use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Log;

class ForgetPasswordService
{
    use ApiResponseTrait;

    public function forgetPassword(array $data): JsonResponse
    {
        try {
            $user = User::where(
                'email',
                $data['email']
            )->first();

            if (!$user) {

                return $this->errorResponse(
                    'User not found.',
                    null,
                    404
                );
            }

            $token = Password::createToken($user);

            return $this->successResponse([
                'token' => $token,
            ], 'Password reset token generated.');
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
