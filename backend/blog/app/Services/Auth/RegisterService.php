<?php

namespace App\Services\Auth;

use App\Http\Resources\UserResource;
use App\Models\User;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\JsonResponse;
use App\Repositories\Contracts\UserRepositoryInterface;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Log;

class RegisterService
{
    use ApiResponseTrait;
    public function __construct(
        protected UserRepositoryInterface $userRepository
    ) {}
    public function register(array $data): JsonResponse
    {
        try {
            /** @var User $user */
            $user = $this->userRepository
                ->create($data);
            $user = User::where('email', $data['email'])->first();

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
            return response()->json([
                'message' => __('User registered successfully,Verification email sent successfully. Please verify'),
                'verification_url' => $verificationUrl,
                'expires_in_minutes' => 60,
                'data' => new UserResource($user),
            ], 201);
        } catch (\Throwable $exception) {
            Log::error('Registration Error', [
                'message' => $exception->getMessage(),
            ]);

            return $this->errorResponse(
                'Unable to process Registration request.',
                null,
                500
            );
        }
    }
}
