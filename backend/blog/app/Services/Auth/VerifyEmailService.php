<?php

namespace App\Services\Auth;

use App\Models\User;
use App\Traits\ApiResponseTrait;
use Illuminate\Auth\Events\Verified;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class VerifyEmailService
{
    use ApiResponseTrait;

    public function verifyEmail(Request $request): JsonResponse
    {
        try {

            $id = $request->route('id');
            $hash = $request->route('hash');

            $user = User::find($id);

            if (!$user) {

                return $this->errorResponse(
                    __('User not found.'),
                    null,
                    404
                );
            }

            /**
             * Validate verification hash
             */
            if (!hash_equals(
                sha1($user->getEmailForVerification()),
                $hash
            )) {

                return $this->errorResponse(
                    __('Invalid verification hash.'),
                    null,
                    403
                );
            }

            /**
             * Validate signed URL
             */
            if (!$request->hasValidSignature()) {

                return $this->errorResponse(
                    __('Invalid or expired verification link.'),
                    null,
                    403
                );
            }

            /**
             * Already verified
             */
            if ($user->hasVerifiedEmail()) {

                return $this->successResponse(
                    null,
                    __('Email already verified.')
                );
            }

            /**
             * Mark email as verified
             */
            if ($user->markEmailAsVerified()) {

                event(new Verified($user));
            }

            return $this->successResponse(
                null,
                __('Email verified successfully.')
            );
        } catch (\Throwable $exception) {

            Log::error('Email Verification Error', [
                'message' => $exception->getMessage(),
                'file' => $exception->getFile(),
                'line' => $exception->getLine(),
            ]);

            return $this->errorResponse(
                __('Unable to process email verification request.'),
                null,
                500
            );
        }
    }
}
