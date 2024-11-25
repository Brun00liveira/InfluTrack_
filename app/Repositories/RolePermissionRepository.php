<?php

namespace App\Repositories;

use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Collection;

class RolePermissionRepository
{
    protected $user;
    protected $permission;
    protected $role;

    public function __construct(User $user, Permission $permission, Role $role)
    {
        $this->user = $user;
        $this->permission = $permission;
        $this->role = $role;
    }

    /**
     * Cria um novo papel (role).
     *
     * @param array $data
     * @return Role
     */
    public function createRole(array $data): Role
    {
        return $this->role->create($data);
    }

    /**
     * Cria uma nova permissão.
     *
     * @param array $data
     * @return Permission
     */
    public function createPermission(array $data): Permission
    {
        return $this->permission->create($data);
    }

    /**
     * Atribui uma permissão a um papel.
     *
     * @param Role $role
     * @param string $permission
     * @return Role
     */
    public function assignPermissionToRole(Role $role, string $permission): Role
    {
        return $role->givePermissionTo($permission);
    }

    /**
     * Obtém um papel pelo nome.
     *
     * @param string $name
     * @return Role|null
     */
    public function getRoleByName(string $name): ?Role
    {
        return $this->role->findByName($name, 'api');
    }

    /**
     * Obtém um papel pelo nome e guard.
     *
     * @param string $name
     * @param array $guards
     * @return Role|null
     */
    public function getRoleByNameAndGuard(string $name, array $guards): ?Role
    {
        return $this->role->where('name', $name)
            ->whereIn('guard_name', $guards)
            ->first();
    }

    /**
     * Obtém uma permissão pelo nome e guard.
     *
     * @param string $name
     * @param array $guards
     * @return Permission|null
     */
    public function getPermissionByNameAndGuard(string $name, array $guards): ?Permission
    {
        return $this->permission->where('name', $name)
            ->whereIn('guard_name', $guards)
            ->first();
    }

    /**
     * Obtém um usuário pelo ID.
     *
     * @param int $userId
     * @return User|null
     */
    public function getUserById(int $userId): ?User
    {
        return $this->user->find($userId);
    }
}
