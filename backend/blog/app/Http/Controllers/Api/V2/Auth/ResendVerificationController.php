<?php

namespace App\Http\Controllers\Api\V2\Auth;

use App\Http\Controllers\Controller;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\URL;

class ResendVerificationController extends Controller
{
    use ApiResponseTrait;

    public function __invoke(Request $request): JsonResponse
    {
        $request->validate([
            'email' => ['required', 'email'],
        ]);

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
    }
}
