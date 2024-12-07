<?php

namespace App\Http\Integrations;

use Illuminate\Support\Facades\Http;

class TranslateIntegration
{
    /**
     * Traduz uma palavra usando a API MyMemory.
     *
     * @param string $text
     * @param string $sourceLang
     * @param string $targetLang
     * @return string|null
     */
    public function translate(string $text, string $sourceLang = 'en', string $targetLang = 'pt')
    {
        // Construa a URL para a API MyMemory
        $url = 'https://api.mymemory.translated.net/get';
        
        // Realiza a requisição para a API
        $response = Http::get($url, [
            'q' => $text,
            'langpair' => "{$sourceLang}|{$targetLang}",
        ]);
        
        if ($response->successful()) {
            $data = $response->json();
            return $data['responseData']['translatedText'] ?? null;
        }

        return null;
    }
}
