<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Http\Requests\ResetPasswordRequest;
use App\Services\UserService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Request;

class AuthController extends Controller
{
    protected $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    /**
     * Realiza o login do usuário.
     */
    public function login(LoginRequest $request): JsonResponse
    {
        $credentials = $request->only(['email', 'password']);

        $user = $this->userService->login($credentials);

        if (! $user) {
            return response()->json(['message' => 'Credenciais inválidas'], 401);
        }

        $token = $user->createToken('Token')->plainTextToken;

        return response()->json(['user' => $user, 'token' => $token], 200);
    }

    /**
     * Realiza o registro de um novo usuário.
     */
    public function register(RegisterRequest $request): JsonResponse
    {
        $user = $this->userService->create($request->all());

        $token = $user->createToken('Token')->plainTextToken;

        return response()->json(['user' => $user, 'token' => $token], 201);
    }

    /**
     * Realiza o logout do usuário.
     */
    public function logout(Request $request): JsonResponse
    {

        $user = Request::user();

        if ($this->userService->logout($user)) {
            return response()->json(['message' => 'Logout realizado com sucesso'], 200);
        }

        return response()->json(['message' => 'Falha ao realizar logout'], 500);
    }

    /**
     * Envia um link de redefinição de senha para o e-mail.
     *
     * @param  SendResetLinkRequest  $request
     */
    public function sendResetLinkEmail(ResetPasswordRequest $request): JsonResponse
    {
        $response = $this->userService->sendResetLink($request->only('email'));

        return response()->json(['message' => $response['message']], $response['status']);
    }

    /**
     * Realiza a redefinição de senha.
     */
    public function reset(ResetPasswordRequest $request): JsonResponse
    {
        $response = $this->userService->resetPassword(
            $request->only('email', 'password', 'password_confirmation', 'token')
        );

        return response()->json(['message' => $response['message']], $response['status']);
    }
}
