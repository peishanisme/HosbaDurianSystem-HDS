<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;

class BlockchainService
{
    public function createSale(array $payload): array
    {
        $url = rtrim(config('services.blockchain.base_url'), '/') . '/add-sale';

        $response = Http::post($url, $payload);

        if ($response->successful()) {
            return [
                'success' => true,
                'txHash' => $response['txHash'],
            ];
        }

        Log::error('Blockchain sync failed', ['response' => $response->body()]);
        return ['success' => false];
    }

    public function createFruit(array $payload): array
    {
        $url = rtrim(config('services.blockchain.base_url'), '/') . '/add-fruit';

        $response = Http::post($url, $payload);

        if ($response->successful()) {
            return [
                'success' => true,
                'txHash' => $response['txHash'],
            ];
        }

        Log::error('Blockchain sync failed', ['response' => $response->body()]);
        return ['success' => false];
    }

    public function getFruitOnChain(string $fruitTag): string
    {
        $url = rtrim(config('services.blockchain.base_url'), '/')
            . '/fruit/' . $fruitTag;

        $response = Http::get($url);

        if (!$response->successful()) {
            throw new \Exception('Blockchain read failed');
        }

        return strtolower($response['metadataHash']);
    }
}
