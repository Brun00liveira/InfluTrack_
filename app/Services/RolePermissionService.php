<?php

namespace App\Services;

use App\Repositories\RolePermissionRepository;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolePermissionService
{
    protected $rolePermissionRepository;

    public function __construct(RolePermissionRepository $rolePermissionRepository)
    {
        $this->rolePermissionRepository = $rolePermissionRepository;
    }

    /**
     * Cria um novo papel (role).
     */
    public function createRole(array $data): Role
    {
        return $this->rolePermissionRepository->createRole($data);
    }

    /**
     * Cria uma nova permissão.
     */
    public function createPermission(array $data): Permission
    {
        return $this->rolePermissionRepository->createPermission($data);
    }

    /**
     * Atribui uma permissão a um papel (role).
     *
     * @return bool|null
     */
    public function assignPermissionToRole(string $roleName, string $permissionName): Role | null
    {
        $role = $this->rolePermissionRepository->getRoleByName($roleName);

        if ($role) {
            return $this->rolePermissionRepository->assignPermissionToRole($role, $permissionName);
        }

        return null;
    }

    /**
     * Atribui um papel (role) a um usuário.
     */
    public function assignRoleToUser(int $userId, string $roleName): bool
    {
        $user = $this->rolePermissionRepository->getUserById($userId);
        $role = $this->rolePermissionRepository->getRoleByNameAndGuard($roleName, ['web', 'api']);

        if ($user && $role) {
            $user->assignRole($role);

            return true;
        }

        return false;
    }

    /**
     * Atribui uma permissão a um usuário.
     */
    public function assignPermissionToUser(int $userId, string $permissionName): bool
    {
        $user = $this->rolePermissionRepository->getUserById($userId);
        $permission = $this->rolePermissionRepository->getPermissionByNameAndGuard($permissionName, ['web', 'api']);

        if ($user && $permission) {
            $user->givePermissionTo($permission);

            return true;
        }

        return false;
    }
}
