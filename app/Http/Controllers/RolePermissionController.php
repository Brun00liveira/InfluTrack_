<?php

namespace App\Http\Controllers;

use App\Http\Requests\RolePermissionNameRequest;
use App\Http\Requests\RolePermissionRequest;
use App\Http\Requests\UserPermissionRequest;
use App\Http\Requests\UserRoleRequest;
use App\Services\RolePermissionService;

class RolePermissionController extends Controller
{
    protected $rolePermissionService;

public function __construct(RolePermissionService $rolePermissionService)
{
$this->rolePermissionService = $rolePermissionService;
}

    /**
     * Cria um novo papel (role).
     */
    public function createRole(RolePermissionNameRequest $request): \Illuminate\Http\JsonResponse
    {
        $role = $this->rolePermissionService->createRole([
            'name' => $request->name,
            'guard_name' => 'api',
        ]);

        return response()->json(['message' => 'Papel criado com sucesso', 'role' => $role], 201);
    }

    /**
     * Cria uma nova permissão.
     */
    public function createPermission(RolePermissionNameRequest $request): \Illuminate\Http\JsonResponse
    {
        $permission = $this->rolePermissionService->createPermission([
            'name' => $request->name,
            'guard_name' => 'api',
        ]);

        return response()->json(['message' => 'Permissão criada com sucesso', 'permission' => $permission], 201);
    }

    /**
     * Atribui uma permissão a um papel (role).
     */
    public function assignPermissionToRole(RolePermissionRequest $request): \Illuminate\Http\JsonResponse
    {
        $result = $this->rolePermissionService->assignPermissionToRole($request->role, $request->permission);

        if ($result) {
            return response()->json(['message' => 'Permissão atribuída ao papel com sucesso'], 200);
        }

        return response()->json(['message' => 'Papel não encontrado'], 404);
    }

    /**
     * Atribui um papel a um usuário.
     */
    public function assignRoleToUser(UserRoleRequest $request): \Illuminate\Http\JsonResponse
    {
        $result = $this->rolePermissionService->assignRoleToUser($request->user_id, $request->role);

        if ($result) {
            return response()->json(['message' => 'Papel atribuído ao usuário com sucesso'], 200);
        }

        return response()->json(['message' => 'Usuário ou papel não encontrado'], 404);
    }

    /**
     * Atribui uma permissão a um usuário.
     */
    public function assignPermissionToUser(UserPermissionRequest $request): \Illuminate\Http\JsonResponse
    {
        // Corrigido o nome do campo para 'permission' no comentário
        $result = $this->rolePermissionService->assignPermissionToUser($request->user_id, $request->permission);

        if ($result) {
            return response()->json(['message' => 'Permissão atribuída ao usuário com sucesso'], 200);
        }

        return response()->json(['message' => 'Usuário ou permissão não encontrado'], 404);
    }
}
