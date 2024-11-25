<?php

namespace App\Repositories;

use App\Models\User;
use App\Models\PasswordResetToken;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Collection;

class UserRepository
{
    protected $user;
    protected $passwordResetToken;

    public function __construct(User $user, PasswordResetToken $passwordResetToken)
    {
        $this->user = $user;
        $this->passwordResetToken = $passwordResetToken;
    }

    /**
     * Cria um novo usuário.
     *
     * @param array $data
     * @return User
     */
    public function create(array $data): User
    {
        return $this->user->create([
            'name' => $data['name'],
            'email' => $data['email'],
            'phone' => $data['phone'],
            'password' => Hash::make($data['password']),
        ]);
    }

    /**
     * Realiza o login de um usuário.
     *
     * @param array $credentials
     * @return User|null
     */
    public function login(array $credentials): ?User
    {
        $user = $this->user->where('email', $credentials['email'])->first();

        if (!$user || !Hash::check($credentials['password'], $user->password)) {
            return null;
        }

        return $user;
    }

    /**
     * Realiza o logout de um usuário.
     *
     * @param User $user
     * @return bool
     */
    public function logout(User $user): bool
    {
        return $user->currentAccessToken()->delete();
    }

    /**
     * Envia um link para resetar a senha do usuário.
     *
     * @param array $data
     * @return \Illuminate\Contracts\Auth\PasswordBroker|string
     */
    public function sendResetLink(array $data): String
    {
        return Password::sendResetLink(['email' => $data['email']]);
    }

    /**
     * Reseta a senha de um usuário.
     *
     * @param array $data
     * @return \Illuminate\Contracts\Auth\PasswordBroker|string
     */
    public function resetPassword(array $data): String
    {
        return Password::reset($data, function (User $user, $password) {
            $user->forceFill([
                'password' => Hash::make($password)
            ])->save();
        });
    }
}
