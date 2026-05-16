<?php

namespace App\Http\Controllers\Api\V1\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Models\User;
use App\Traits\ApiResponseTrait;

class UserController extends Controller
{
    use ApiResponseTrait;
    public function __invoke(Request $request): JsonResponse
    {
        return $this->successResponse([
            'message' => __('User List Here'),

            'token_type' => 'Bearer',
            'data' => [
                'user' => User::all(),
            ],
        ], 200);
    }
}
