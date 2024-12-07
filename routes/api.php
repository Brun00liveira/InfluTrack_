<?php

use App\Http\Controllers\Auth\{
    AuthController
};

use App\Http\Controllers\{
    RolePermissionController
};



use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::post('/create', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::post('/password/forgot', [AuthController::class, 'sendResetLinkEmail'])->name('password.reset');
Route::post('/password/reset', [AuthController::class, 'reset']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
});

Route::middleware(['auth:sanctum', 'role:admin'])->group(function () {
    Route::post('/roles', [RolePermissionController::class, 'createRole']);
    Route::post('/permissions', [RolePermissionController::class, 'createPermission']);

    Route::post('/roles/role-permission', [RolePermissionController::class, 'assignPermissionToRole']);
    Route::post('/users/assign-role', [RolePermissionController::class, 'assignRoleToUser']);
    Route::post('/users/assign-permission', [RolePermissionController::class, 'assignPermissionToUser']);
});

Route::resource('users', UserController::class);

Route::get('/phpinfo', function () {
    phpinfo();
});
