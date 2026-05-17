<?php

namespace App\Http\Controllers\Api\V2\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use App\Http\Requests\Auth\ForgotPasswordRequest;
use Illuminate\Support\Facades\Log;

class ForgotPasswordController extends Controller
{
    use ApiResponseTrait;

    public function __invoke(ForgotPasswordRequest $request): JsonResponse
    {
        try {
            $user = User::where(
                'email',
                $request->email
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
