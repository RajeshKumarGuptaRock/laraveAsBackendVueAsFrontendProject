<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;
use App\Traits\ApiResponseTrait;

class LoginController extends Controller
{
    use ApiResponseTrait;
    public function __invoke(LoginRequest $request): JsonResponse
    {
        /** @var User|null $user */
        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {

            return $this->errorResponse(
                'Invalid credentials',
                null,
                401
            );
        }

        $token = $user->createToken('api-token')->plainTextToken;

        return response()->json([
            'message' => __('Login successful'),
            'data' => [
                'user' => new UserResource($user),
                'token' => $token,
                'token_type' => 'Bearer',
            ],
        ], 200);
    }
}
