<?php

namespace App\Services;

use App\Models\User;
use App\Repositories\UserRepository;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

class UserService
{
    protected $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function getAllUsers(int $perPage = 10,  $query = null): LengthAwarePaginator | Collection
    {
        return $this->userRepository->getAll($perPage, $query);
    }

    public function getUserById(int $id): ?User
    {
        return $this->userRepository->findById($id);
    }

    public function createUser(array $data): User
    {
        return $this->userRepository->create($data);
    }

    public function updateUser(int $id, array $data): User
    {
        $user = $this->userRepository->findById($id);

        return $this->userRepository->update($user, $data);
    }

    public function deleteUser(int $id): bool
    {
        $user = $this->userRepository->findById($id);
        return $this->userRepository->delete($user);
    }
}