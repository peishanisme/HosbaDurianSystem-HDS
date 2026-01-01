<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class LibreTranslateService
{
    protected $endpoint = 'https://libretranslate.com/translate';

    public function translate($text, $target, $source = 'en')
    {
        $response = Http::post($this->endpoint, [
            'q' => $text,
            'source' => $source,
            'target' => $target,
            'format' => 'text'
        ]);

        return $response->json()['translatedText'] ?? null;
    }
}
