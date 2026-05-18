<?php

namespace App\Http\Controllers\Api\V1\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Traits\ApiResponseTrait;
use App\Services\Auth\LogoutService;

class LogoutController extends Controller
{
    use ApiResponseTrait;
    public function __invoke(
        Request $request,
        LogoutService $service
    ): JsonResponse {

        return $service->logout($request);
    }
}
