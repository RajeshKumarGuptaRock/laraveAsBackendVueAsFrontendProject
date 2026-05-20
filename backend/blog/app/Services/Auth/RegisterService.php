<?php

namespace App\Services\Auth;

use App\Http\Resources\UserResource;
use App\Models\User;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;
use App\Repositories\Contracts\UserRepositoryInterface;

class RegisterService
{
    use ApiResponseTrait;
    public function __construct(
        protected UserRepositoryInterface $userRepository
    ) {}
    public function register(array $data): JsonResponse
    {
        /** @var User $user */
        // $user = User::create([
        //     'name' => $data['name'],
        //     'email' => $data['email'],
        //     'password' => Hash::make($data['password']),
        // ]);
        $user = $this->userRepository
            ->create($data);
        return response()->json([
            'message' => __('User registered successfully'),
            'data' => new UserResource($user),
        ], 201);
    }
}
