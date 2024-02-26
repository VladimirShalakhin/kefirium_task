<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\RegisterRequest;
use App\Services\UserService;
use Illuminate\Http\JsonResponse;

class UserController extends Controller
{
    public function __construct(
        protected UserService $userService
    ) {
        $this->middleware('auth:api', ['except' => ['register']]);
    }

    /**
     * Регистрация пользователя
     * @param RegisterRequest $request
     * @return JsonResponse
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
}
