<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Http\Requests\ResetPasswordRequest;
use App\Services\AuthService;
use App\Helpers\ApiResponse;

use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Request;

class AuthController extends Controller
{
    protected $authService;

    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }

    /**
     * Realiza o registro de um novo usuário.
     *
     * @param RegisterRequest $request
     * @return JsonResponse
     */
    public function register(RegisterRequest $request): JsonResponse
    {
        $user = $this->authService->create($request->all());

        $token = $user->createToken('Token')->plainTextToken;

        return ApiResponse::success(
            ['user' => $user, 'token' => $token],
            'Usuário registrado com sucesso',
            201
        );
    }

    /**
     * Realiza o login do usuário.
     */
    public function login(LoginRequest $request): JsonResponse
    {
        $credentials = $request->only(['email', 'password']);

        $user = $this->authService->login($credentials);

        if (!$user) {
            return ApiResponse::error(

                'Usuário não encontrado',
                404
            );
        }

        $token = $user->createToken('Token')->plainTextToken;

        return ApiResponse::success(
            ['user' => $user, 'token' => $token],
            'Usuário registrado com sucesso',
            200
        );
    }



    /**
     * Realiza o logout do usuário.
     */
    public function logout(Request $request): JsonResponse
    {

        $user = Request::user();

        // Tente realizar o logout
        if ($this->authService->logout($user)) {
            return ApiResponse::success(
                [],
                'Logout realizado com sucesso',
                200

            );
        }

        return ApiResponse::error(
            'Falha ao realizar o Logout',
            401
        );
    }


    /**
     * Envia um link de redefinição de senha para o e-mail.
     *
     * @param ResetPasswordRequest $request
     * @return JsonResponse
     */
    public function sendResetLinkEmail(ResetPasswordRequest $request): JsonResponse
    {
        $response = $this->authService->sendResetLink($request->only('email'));

        return response()->json(['message' => $response['message']], $response['status']);
    }

    /**
     * Realiza a redefinição de senha.
     */
    public function reset(ResetPasswordRequest $request): JsonResponse
    {
        $response = $this->authService->resetPassword(
            $request->only('email', 'password', 'password_confirmation', 'token')
        );

        return response()->json(['message' => $response['message']], $response['status']);
    }
}
