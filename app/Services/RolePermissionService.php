<?php

namespace App\Services;

use App\Repositories\RolePermissionRepository;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolePermissionService
{
    protected $rolePermissionRepository;

    public function __construct(RolePermissionRepository $rolePermissionRepository)
    {
        $this->rolePermissionRepository = $rolePermissionRepository;
    }

    public function createRole(array $data)
    {
        return $this->rolePermissionRepository->createRole($data);
    }

    public function createPermission(array $data)
    {
        return $this->rolePermissionRepository->createPermission($data);
    }

    public function assignPermissionToRole(string $roleName, string $permissionName)
    {
        $role = $this->rolePermissionRepository->getRoleByName($roleName);

        if ($role) {
            return $this->rolePermissionRepository->assignPermissionToRole($role, $permissionName);
        }
        return null;
    }

    public function assignRoleToUser(int $userId, string $roleName)
    {

        $user = $this->rolePermissionRepository->getUserById($userId);
        $role = $this->rolePermissionRepository->getRoleByNameAndGuard($roleName, ['web', 'api']);

        if ($user && $role) {
            $user->assignRole($role);
            return true;
        }
        return false;
    }

    public function assignPermissionToUser(int $userId, string $permissionName)
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
