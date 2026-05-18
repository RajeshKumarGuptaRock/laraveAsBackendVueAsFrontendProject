<?php

namespace App\Http\Controllers\Api\V1\Auth;

use App\Http\Controllers\Controller;
use App\Services\Auth\VerifyEmailService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class VerifyEmailController extends Controller
{
    public function __invoke(
        Request $request,
        VerifyEmailService $service
    ): JsonResponse {

        return $service->verifyEmail($request);
    }
}
