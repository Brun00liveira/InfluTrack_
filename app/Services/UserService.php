<?php

namespace App\Services;

use App\Repositories\UserRepository;
use Illuminate\Support\Facades\Password;
use App\Models\User;

class UserService
{
    protected $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /**
     * Cria um novo usuário através do repositório.
     *
     * @param array $data
     * @return User
     */
    public function create(array $data): User
    {
        return $this->userRepository->create($data);
    }

    /**
     * Realiza o login de um usuário.
     *
     * @param array $credentials
     * @return User|null
     */
    public function login(array $credentials): ?User
    {
        return $this->userRepository->login($credentials);
    }

    /**
     * Realiza o logout de um usuário.
     *
     * @param User $user
     * @return bool
     */
    public function logout(User $user): bool
    {
        return $this->userRepository->logout($user);
    }

    /**
     * Envia um link para redefinição de senha.
     *
     * @param array $data
     * @return array
     */
    public function sendResetLink(array $data): array
    {
        $status = $this->userRepository->sendResetLink($data);

        if ($status === Password::RESET_LINK_SENT) {
            return ['message' => 'Link de redefinição de senha enviado para seu email.', 'status' => 200];
        }

        return ['message' => 'Não foi possível enviar o link de redefinição.', 'status' => 500];
    }

    /**
     * Redefine a senha do usuário.
     *
     * @param array $data
     * @return array
     */
    public function resetPassword(array $data): array
    {
        $status = $this->userRepository->resetPassword($data);

        if ($status === Password::PASSWORD_RESET) {
            return ['message' => 'Senha redefinida com sucesso.', 'status' => 200];
        }

        return ['message' => 'Falha ao redefinir a senha.', 'status' => 500];
    }
}
