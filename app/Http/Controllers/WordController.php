<?php

namespace App\Http\Controllers;

use App\Http\Integrations\TranslateIntegration;

use App\Services\WordService;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class WordController extends Controller
{
    protected $wordService;
    protected $translateService;

    public function __construct(WordService $wordService, TranslateIntegration $translateService)
    {
        $this->wordService = $wordService;
        $this->translateService = $translateService;
    }

    public function index()
    {
        $words = $this->wordService->getAllWords();
        return response()->json($words);
    }

    public function show($id)
    {
        try {
            $word = $this->wordService->getWordById($id);
            return response()->json($word);
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'Word not found'], 404);
        }
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'en_word' => 'required|string',
        ]);
        $translatedWord = $this->translateService->translate($data['en_word'], 'en', 'pt');

       if ($translatedWord) {
            // Salva a palavra traduzida no banco de dados
            $word = $this->wordService->createWord([
                'pt_word' => $translatedWord,
                'en_word' => $data['en_word'],
            ]);
            return response()->json($word, 201);
        }

        return response()->json(['message' => 'Translation failed'], 500);
    }

    public function update(Request $request, $id)
    {
        $data = $request->validate([
            'pt_word' => 'required|string',
            'en_word' => 'required|string',
          
        ]);

        try {
            $word = $this->wordService->updateWord($id, $data);
            return response()->json($word);
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'Word not found'], 404);
        }
    }

    public function destroy($id)
    {
        try {
            $this->wordService->deleteWord($id);
            return response()->json(['message' => 'Word deleted successfully']);
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'Word not found'], 404);
        }
    }
}
