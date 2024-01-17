<?php

namespace App\Http\Controllers;

use App\Exceptions\NotFoundException;
use App\Http\Requests\Auth\LoginUserRequest;
use App\Http\Requests\Auth\RegisterUserRequest;
use App\Services\AuthService;
use Illuminate\Http\JsonResponse;

class AuthController extends BaseApiController
{
    public function __construct(
        protected AuthService $authService,
    ) {}

    public function login(LoginUserRequest $request): JsonResponse
    {
        try {
            $user = $this->authService->authenticate(
                email: $request->validated('email'),
                password: $request->validated('password'),
            );
        } catch (NotFoundException $e) {
            return $this->failedResponse($e->getMessage(), $e->getCode());
        }

        return $this->successResponse([
            'email' => $user->email,
            'token' => $user->createToken('auth_token')->plainTextToken,
        ]);
    }

    public function register(RegisterUserRequest $request): JsonResponse
    {
        try {
            $user = $this->authService->register($request->validated());
        } catch (\Error $e) {
            return $this->failedResponse($e->getMessage(), $e->getCode());
        }

        return $this->successResponse([
            'email' => $user->email,
            'token' => $user->createToken('auth_token')->plainTextToken,
        ], 201);
    }

    public function logout(): JsonResponse
    {
        $this->authService->logout();

        return $this->successResponse('ok');
    }
}
