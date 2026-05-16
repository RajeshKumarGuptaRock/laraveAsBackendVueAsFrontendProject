<?php

namespace App\Http\Controllers\Api\Auth;

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
        // return response()->json([
        //     'message' => __('User List'),
        //     'data' => [
        //         'user' => User::all(),
        //     ],
        // ], 200);


        return $this->successResponse([
            'message' => __('User List'),

            'token_type' => 'Bearer',
            'data' => [
                'user' => User::all(),
            ],
        ], 200);
    }
}
