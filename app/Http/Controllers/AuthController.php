<?php

namespace App\Http\Controllers;

use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Http\Resources\UserResource;
use App\Services\AuthService;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    use ApiResponse;

    public function __construct(
        private AuthService $authService
    ) {
    }


    /**
     * Register new user.
     */
    public function register(
        RegisterRequest $request
    ): JsonResponse {
        $result = $this->authService->register(
            $request->validated()
        );

        return $this->success(
            [
                'token' => $result['token'],
                'user' => new UserResource($result['user']),
            ],
            'Register success',
            201
        );
    }


    /**
     * Login user.
     */
    public function login(
        LoginRequest $request
    ): JsonResponse {
        $result = $this->authService->login(
            $request->validated()
        );

        return $this->success(
            [
                'token' => $result['token'],
                'user' => new UserResource($result['user']),
            ],
            'Login success'
        );
    }


    /**
     * Logout current user.
     */
    public function logout(
        Request $request
    ): JsonResponse {
        $this->authService->logout(
            $request->user()
        );

        return $this->success(
            null,
            'Logout success'
        );
    }


    /**
     * Get current user.
     */
    public function me(
        Request $request
    ): JsonResponse {
        $user = $this->authService->me(
            $request->user()
        );

        return $this->success(
            new UserResource($user),
            'User retrieved successfully'
        );
    }
}