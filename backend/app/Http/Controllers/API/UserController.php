<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\API\LoginRequest;
use App\Http\Requests\Api\RegisterRequest;
use App\Services\UserService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use PHPOpenSourceSaver\JWTAuth\Facades\JWTAuth;

class UserController extends Controller
{
    public function __construct(
        protected UserService $userService
    ) {
        $this->middleware('auth:api', ['except' => ['register', 'login', 'refresh']]);
    }

    /**
     * Регистрация пользователя
     */
    public function register(RegisterRequest $request): JsonResponse
    {
        $email = $request->input('email');
        $password = $request->input('password');

        $this->userService->create($email, $password);

        return response()->json([
            'message' => 'Пользователь успешно создан.',
        ], 201);
    }

    public function login(LoginRequest $request): JsonResponse
    {
        $email = $request->input('email');
        $password = $request->input('password');
        $token = Auth::attempt(['email' => $email, 'password' => $password]);
        $userInfo = $this->userService->createNewToken($token);

        return response()->json([
            'access_token' => $userInfo['token'],
            'token_type' => 'bearer',
            'expires_in' => JWTAuth::factory()->getTTL() * 60,
            'user' => $userInfo['user'],
        ]);
    }

    public function refresh(): JsonResponse
    {
        $userInfo = $this->userService->createNewToken(Auth::refresh());

        return response()->json([
            'access_token' => $userInfo['token'],
            'token_type' => 'bearer',
            'expires_in' => JWTAuth::factory()->getTTL() * 60,
            'user' => $userInfo['user'],
        ]);
    }
}
