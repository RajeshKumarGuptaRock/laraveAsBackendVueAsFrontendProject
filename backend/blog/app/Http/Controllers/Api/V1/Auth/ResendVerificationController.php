<?php

namespace App\Http\Controllers\Api\V1\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use App\Http\Requests\Auth\ResendVerificationRequest;
use App\Services\Auth\ResendVerificationService;


class ResendVerificationController extends Controller
{


    public function __invoke(ResendVerificationRequest $request, ResendVerificationService $service): JsonResponse
    {
        return $service->resendVerification($request->validated());
    }
}
