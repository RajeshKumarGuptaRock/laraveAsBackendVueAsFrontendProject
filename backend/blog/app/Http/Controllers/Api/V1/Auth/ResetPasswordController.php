<?php

namespace App\Http\Controllers\Api\V1\Auth;

use App\Http\Controllers\Controller;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\JsonResponse;
use App\Http\Requests\Auth\ResetPasswordRequest;
use App\Services\Auth\ResetPasswordService;


class ResetPasswordController extends Controller
{
    use ApiResponseTrait;

    public function __invoke(ResetPasswordRequest $request, ResetPasswordService $service): JsonResponse
    {
        return $service->resetPassword($request->validated());
    }
}
