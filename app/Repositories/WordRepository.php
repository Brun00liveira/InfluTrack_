<?php

namespace App\Repositories;

use App\Models\Word;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class WordRepository
{
   
    protected $word;

    public function __construct(Word $word)
    {
        $this->word = $word;
    }

    public function all(): Collection
    {
        return $this->word->all();
    }

    /**
     * Obter uma palavra pelo ID
     *
     * @param int $id
     * @return Word
     * @throws ModelNotFoundException
     */
    public function find(int $id): Word
    {
        return $this->word->findOrFail($id);  // Lança ModelNotFoundException se não encontrar
    }

    /**
     * Criar uma nova palavra
     *
     * @param array $data
     * @return Word
     */
    public function create(array $data): Word
    {
     
        return $this->word->updateOrCreate(
            [
                'pt_word' => $data['pt_word'], 
                'en_word' => $data['en_word']  
            ],
           
        );
    }
    

    /**
     * Atualizar uma palavra existente
     *
     * @param int $id
     * @param array $data
     * @return Word
     * @throws ModelNotFoundException
     */
    public function update(int $id, array $data): Word
    {
        $word = $this->find($id);
        $word->update($data);
        return $word;
    }

    /**
     * Deletar uma palavra pelo ID
     *
     * @param int $id
     * @return bool
     * @throws ModelNotFoundException
     */
    public function delete(int $id): bool
    {
        $word = $this->find($id);
        return $word->delete();
    }
}
