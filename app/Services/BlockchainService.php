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
}
