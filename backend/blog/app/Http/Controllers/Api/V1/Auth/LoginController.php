<?php

namespace App\Http\Controllers\Api\V1\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Services\Auth\LoginService;
use Illuminate\Http\JsonResponse;
use App\Traits\ApiResponseTrait;

class LoginController extends Controller
{
    use ApiResponseTrait;
    public function __invoke(LoginRequest $request, LoginService $service): JsonResponse
    {
        return $service->login($request->validated());
    }
}
