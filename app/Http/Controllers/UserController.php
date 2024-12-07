<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\PhotoRequest;
use App\Http\Requests\UserRequest;
use App\Http\Resources\StandardResource;
use App\Services\UserService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{
    protected $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    public function index(Request $request): StandardResource
    {
        $perPage = $request->query('perPage', 10);

        $users = $this->userService->getAllUsers($perPage, $request->all());

        return new StandardResource($users);
    }

    public function show($id): StandardResource
    {
        $user = $this->userService->getUserById($id);
        return new StandardResource($user);
    }

    public function store(UserRequest $request): StandardResource
    {
        // Verifica se a foto foi enviada e é válida
        if ($request->hasFile('photo') && $request->file('photo')->isValid()) {
            // Armazena a foto e obtém o caminho relativo
            $path = $request->file('photo')->store('users', 'public');
        } else {
            $path = null;
        }
        $validatedData = $request->validated();
        $validatedData['photo'] = $path;

        $user = $this->userService->createUser($validatedData);

        return new StandardResource($user);
    }

    public function update(UserRequest $request, $id): StandardResource
    {
        $user = $this->userService->updateUser($id, $request->validated());

        return new StandardResource($user);
    }

    public function updatePhoto(PhotoRequest $request, $id): StandardResource
    {

        $course = $this->userService->getUserById($id);

        if ($course && $course->photo) {
            $oldPhotoPath = $course->photo;

            if (Storage::exists($oldPhotoPath)) {
                Storage::delete($oldPhotoPath);
            }
        }

        if ($request->hasFile('photo') && $request->file('photo')->isValid()) {
            $path = $request->file('photo')->store('users', 'public');
        } else {
            $path = null;
        }

        $course = $this->userService->updateUser($id, [
            "photo" => $path
        ]);

        return new StandardResource($course);
    }

    public function destroy($id): JsonResponse
    {
        $this->userService->deleteUser($id);
        return response()->json(['message' => 'Usuário excluído com sucesso'], 200);
    }
}