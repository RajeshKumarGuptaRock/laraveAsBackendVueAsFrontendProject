<?php

namespace App\Http\Controllers\Api\V1\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;

class ForgotPasswordController extends Controller
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

        $token = Password::createToken($user);

        return $this->successResponse([
            'token' => $token,
        ], 'Password reset token generated.');
    }
}
