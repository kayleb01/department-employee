<?php

namespace App\Http\Controllers;

use App\Http\Requests\RegisterRequest;
use App\Http\Requests\User\LoginRequest;
use App\Http\Resources\UserResource;
use App\Http\Services\AuthService;

class AuthController extends Controller
{
    protected AuthService $authService;

    /**
     * Create controller instance
     *
     * @param AuthService $authService
     */
    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }

    /**
     * login user
     *
     * @param LoginRequest $request
     * @return \Illuminate\Http\Response|\Illuminate\Contracts\Routing\ResponseFactory
     */
    public function login(LoginRequest $request)
    {
        $userLogin = $this->authService->authenticate($request->validated());

        return response()->json([
            'message' => 'Login successful',
            'data' => $userLogin
        ]);
    }

    /**
     * Register a user
     *
     * @param RegisterRequest $request
     * @return Illuminate\Http\Response|\Illuminate\Contracts\Routing\ResponseFactory
     */
    public function register(RegisterRequest $request)
    {
        $user = $this->authService->register($request->validated());

        return response()->json([
            'message' => 'Registration successful',
            'data' => new UserResource($user)
        ]);
    }

    public function logout()
    {
        auth()->logout();

        return response()->json([
            'message' => 'Logout successful',
            'data' => null
         ]);
    }

}
