<?php

namespace App\Services\Auth;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Traits\ApiResponseTrait;

class LogoutService
{
    use ApiResponseTrait;

    public function logout(Request $request): JsonResponse
    {
        $request->user()
            ->currentAccessToken()
            ->delete();

        return $this->successResponse(
            null,
            __('Logout successful')
        );
    }
}
