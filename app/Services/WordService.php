<?php

namespace App\Services;

use App\Repositories\WordRepository;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class WordService
{
    protected $wordRepository;

    public function __construct(WordRepository $wordRepository)
    {
        $this->wordRepository = $wordRepository;
    }

    /**
     * Obter todas as palavras.
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getAllWords()
    {
        return $this->wordRepository->all();
    }

    /**
     * Obter uma palavra pelo ID.
     *
     * @param int $id
     * @return \App\Models\Word
     * @throws ModelNotFoundException
     */
    public function getWordById(int $id)
    {
        return $this->wordRepository->find($id);
    }

    /**
     * Criar uma nova palavra.
     *
     * @param array $data
     * @return \App\Models\Word
     */
    public function createWord(array $data)
    {
        // Aqui você pode adicionar lógica de negócios, como validações ou formatação
        return $this->wordRepository->create($data);
    }

    /**
     * Atualizar uma palavra existente.
     *
     * @param int $id
     * @param array $data
     * @return \App\Models\Word
     * @throws ModelNotFoundException
     */
    public function updateWord(int $id, array $data)
    {
        // Aqui você pode adicionar lógica adicional, como verificação de formato de dados
        return $this->wordRepository->update($id, $data);
    }

    /**
     * Deletar uma palavra pelo ID.
     *
     * @param int $id
     * @return bool
     * @throws ModelNotFoundException
     */
    public function deleteWord(int $id)
    {
        return $this->wordRepository->delete($id);
    }
}
