<?php

namespace App\Http\Controllers\Api\V1\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Traits\ApiResponseTrait;
use Illuminate\Auth\Events\Verified;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;

class VerifyEmailController extends Controller
{
    use ApiResponseTrait;

    public function __invoke(
        Request $request,
        int $id,
        string $hash
    ): JsonResponse {

        $user = User::find($id);

        if (!$user) {

            return $this->errorResponse(
                'User not found.',
                null,
                404
            );
        }

        /**
         * Validate hash
         */
        if (!hash_equals(
            sha1($user->getEmailForVerification()),
            $hash
        )) {

            return $this->errorResponse(
                'Invalid verification hash.',
                null,
                403
            );
        }

        /**
         * Validate signed URL
         */
        if (!$request->hasValidSignature()) {

            return $this->errorResponse(
                'Invalid or expired verification link.',
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
                'Email already verified.'
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
            'Email verified successfully.'
        );
    }
}
