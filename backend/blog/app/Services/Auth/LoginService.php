<?php

namespace App\Services\Auth;

use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;
use App\Traits\ApiResponseTrait;
use Illuminate\Support\Facades\Log;
use App\Repositories\Contracts\UserRepositoryInterface;

class LoginService
{
    use ApiResponseTrait;
    public function __construct(
        protected UserRepositoryInterface $userRepository
    ) {}
    public function login(array $data): JsonResponse
    {
        try {
            /** @var User|null $user */
            $user = $this->userRepository
                ->findByEmail($data['email']);

            // Optional check verification of email during login
            if (!$user->hasVerifiedEmail()) {
                return $this->errorResponse(
                    __('Please verify your email address.'),
                    null,
                    403
                );
            }


            if (!$user || !Hash::check($data['password'], $user->password)) {

                return $this->errorResponse(
                    __('Invalid credentials.'),
                    null,
                    401
                );
            }
            $user->tokens()->delete();
            $token = $user->createToken('api-token')->plainTextToken;

            return $this->successResponse(
                [
                    'user' => new UserResource($user),
                    'token' => $token,
                    'token_type' => 'Bearer',
                ],
                __('Login successful')
            );
        } catch (\Throwable $exception) {

            Log::error('Login Error', [
                'message' => $exception->getMessage(),
                'file' => $exception->getFile(),
                'line' => $exception->getLine(),
            ]);

            return $this->errorResponse(
                __('Unable to process login request.'),
                null,
                500
            );
        }
    }
}
