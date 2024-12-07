<?php

namespace App\Repositories;

use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

class UserRepository
{
    protected $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    public function getAll(int $perPage = 10, $query = null): LengthAwarePaginator|Collection
    {
        // Inicia a consulta com as relações
        $dataQuery = $this->user->newQuery();

        if ($query && isset($query['name'])) {
            $dataQuery->where('name', 'like', '%' . $query['name'] . '%');
        }
        // Verifica se a consulta inclui uma página para paginação
        if ($query && isset($query['page'])) {
            return $dataQuery->paginate($perPage);  // Retorna a página com o número de registros por página
        }

        // Caso contrário, retorna todos os registros sem paginação
        return $dataQuery->get();  // Usamos $dataQuery aqui para garantir que as relações sejam carregadas
    }


    public function findById($id): User
    {
        return $this->user->findOrFail($id);
    }

    public function create(array $data): User
    {
        return $this->user->create($data);
    }

    public function update(User $user, array $data): User
    {

        $user->update($data);
        return $user;
    }

    public function delete(User $user): bool
    {
        return $user->delete();
    }

    public function countUser(): array
    {
        $users = $this->user->count();
        $activeUser = $this->user->where('is_active', '1')->count();
        $inativeUser = $this->user->where('is_active', '!=', '1')->count();

        return [
            'totalUsers' => $users,
            'inativeUsers' => $inativeUser,
            'activeUser' => $activeUser,
        ];
    }
}