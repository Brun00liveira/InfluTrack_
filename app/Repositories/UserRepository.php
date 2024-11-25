<?php

namespace App\Repositories;

use App\Models\PasswordResetToken;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;

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
     */
    public function login(array $credentials): ?User
    {
        $user = $this->user->where('email', $credentials['email'])->first();

        if (! $user || ! Hash::check($credentials['password'], $user->password)) {
            return null;
        }

        return $user;
    }

    /**
     * Realiza o logout de um usuário.
     */
    public function logout(User $user): bool
    {
        return $user->currentAccessToken()->delete();
    }

    /**
     * Envia um link para resetar a senha do usuário.
     *
     * @return \Illuminate\Contracts\Auth\PasswordBroker|string
     */
    public function sendResetLink(array $data): string
    {
        return Password::sendResetLink(['email' => $data['email']]);
    }

    /**
     * Reseta a senha de um usuário.
     *
     * @return \Illuminate\Contracts\Auth\PasswordBroker|string
     */
    public function resetPassword(array $data): string
    {
        return Password::reset($data, function (User $user, $password) {
            $user->forceFill([
    'password' => Hash::make($password)
            ])->save();
        });
    }
}
