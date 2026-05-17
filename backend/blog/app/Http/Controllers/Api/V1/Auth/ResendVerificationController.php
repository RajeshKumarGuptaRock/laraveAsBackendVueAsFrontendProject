<?php

namespace App\Http\Controllers\Api\V1\Auth;

use App\Http\Controllers\Controller;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\URL;
use App\Http\Requests\Auth\ResendVerificationRequest;
use Illuminate\Support\Facades\Log;

class ResendVerificationController extends Controller
{
    use ApiResponseTrait;

    public function __invoke(ResendVerificationRequest $request): JsonResponse
    {
        try {
            $user = User::where('email', $request->email)->first();

            if (!$user) {

                return $this->errorResponse('User not found.', null, 404);
            }

            if ($user->hasVerifiedEmail()) {

                return $this->errorResponse(
                    'Email already verified.',
                    null,
                    422
                );
            }

            /**
             * Generate signed verification URL
             */
            $verificationUrl = URL::temporarySignedRoute(
                'verification.verify',
                now()->addMinutes(60),
                [
                    'id' => $user->id,
                    'hash' => sha1(
                        $user->getEmailForVerification()
                    ),
                ]
            );

            /**
             * Optional:
             * Send verification email
             */
            $user->sendEmailVerificationNotification();

            return $this->successResponse([
                'verification_url' => $verificationUrl,
                'expires_in_minutes' => 60,
            ], 'Verification email sent successfully.');
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
