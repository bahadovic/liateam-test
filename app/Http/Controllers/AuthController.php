<?php

namespace App\Http\Controllers;


use App\Http\Requests\AuthLoginRequest;
use App\Http\Requests\AuthRefreshTokenRequest;
use App\Http\Requests\RegisterRequest;
use App\Services\AuthService;
use Illuminate\Http\JsonResponse;

class AuthController extends Controller
{
    public function __construct(
        private readonly AuthService $service
    )
    {
    }

    public function register(RegisterRequest $request): JsonResponse
    {
        $data = $this->service->register(params: $request->safe()->toArray());

        return response()->json(data: $data['data'], status: $data['httpStatusCode']);
    }

    public function login(AuthLoginRequest $request): JsonResponse
    {
        $data = $this->service->login(params: $request->safe()->toArray());

        return response()->json(data: $data['data'], status: $data['httpStatusCode']);
    }

    public function logout(): JsonResponse
    {
        $data = $this->service->logout();

        return response()->json(data: $data['data'], status: $data['httpStatusCode']);
    }

    public function refreshToken(AuthRefreshTokenRequest $request): JsonResponse
    {
        $data = $this->service->refreshToken(params: $request->safe()->toArray());

        return response()->json(data: $data['data'], status: $data['httpStatusCode']);
    }
}
