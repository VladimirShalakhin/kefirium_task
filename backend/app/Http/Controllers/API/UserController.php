<?php

namespace App\Http\Controllers\API;

use App\Exceptions\UnauthorizedException;
use App\Http\Controllers\Controller;
use App\Http\Requests\API\LoginRequest;
use App\Http\Requests\Api\RegisterRequest;
use App\Services\UserService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;
use PHPOpenSourceSaver\JWTAuth\Facades\JWTAuth;
use Symfony\Component\HttpFoundation\RedirectResponse;

class UserController extends Controller
{
    public function __construct(
        protected UserService $userService
    ) {
        $this->middleware('auth:api', ['except' => ['register', 'login', 'refresh', 'loginGoogle']]);
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

    /**
     * @throws UnauthorizedException
     */
    public function login(LoginRequest $request): JsonResponse
    {
        $email = $request->input('email');
        $password = $request->input('password');
        $token = Auth::attempt(['email' => $email, 'password' => $password]);

        if (! $token) {
            throw new UnauthorizedException();
        }

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

    public function logout(): JsonResponse
    {
        \auth()->logout();

        return response()->json(['message' => 'Успешно произведен выход']);
    }

    public function loginGoogle(): RedirectResponse|\Illuminate\Http\RedirectResponse
    {
        return Socialite::driver('google')->redirect();
    }

    public function loginOrRegisterGoogle(): void
    {
        $this->userService->loginGoogle();
    }
}
