<?php

namespace App\Services\Auth;

use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;
use App\Traits\ApiResponseTrait;

class LoginService
{
    use ApiResponseTrait;

    public function login(array $data): JsonResponse
    {
        /** @var User|null $user */
        $user = User::where('email', $data['email'])->first();

        if (!$user || !Hash::check($data['password'], $user->password)) {

            return $this->errorResponse(
                'Invalid credentials',
                null,
                401
            );
        }

        $token = $user->createToken('api-token')->plainTextToken;

        return $this->successResponse(
            [
                'user' => new UserResource($user),
                'token' => $token,
                'token_type' => 'Bearer',
            ],
            __('Login successful')
        );
    }
}
