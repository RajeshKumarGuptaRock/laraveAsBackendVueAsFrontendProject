<?php

namespace App\Http\Controllers\Api\V1\Auth;

use App\Http\Controllers\Controller;

use App\Traits\ApiResponseTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use App\Http\Requests\Auth\ForgotPasswordRequest;
use App\Services\Auth\ForgetPasswordService;

class ForgotPasswordController extends Controller
{
    use ApiResponseTrait;

    public function __invoke(ForgotPasswordRequest $request, ForgetPasswordService $service): JsonResponse
    {
        return $service->forgetPassword($request->validated());
    }
}
