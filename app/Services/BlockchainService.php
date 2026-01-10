<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;

class BlockchainService
{
    // --------------------
    // SALES
    // --------------------

    public function createSale(string $transactionId, string $transactionHash): array
    {
        $url = rtrim(config('services.blockchain.base_url'), '/') . '/add-sale';

        $response = Http::post($url, [
            'transactionId'   => $transactionId,
            'transactionHash' => $transactionHash,
        ]);

        if ($response->successful()) {
            Log::info('Blockchain sale sync successful', [
                'response' => $response->body(),
            ]);
            return [
                'success' => true,
                'txHash'  => $response['txHash'],
            ];
        }

        Log::error('Blockchain sale sync failed', [
            'response' => $response->body(),
        ]);

        return ['success' => false];
    }

    public function cancelSale(string $transactionId): array
    {
        $url = rtrim(config('services.blockchain.base_url'), '/') . '/cancel-sale';

        $response = Http::post($url, [
            'transactionId' => $transactionId,
        ]);

        if ($response->successful()) {
            return [
                'success' => true,
                'txHash'  => $response['txHash'],
            ];
        }

        Log::error('Blockchain cancel failed', [
            'response' => $response->body(),
        ]);

        return ['success' => false];
    }

    public function verifySale(string $transactionId, string $transactionHash): array
    {
        $url = rtrim(config('services.blockchain.base_url'), '/')
            . '/verify-sale/' . $transactionId . '/' . $transactionHash;

        $response = Http::get($url);

        if (!$response->successful()) {
            throw new \Exception('Blockchain verification failed');
        }

        return [
            'valid'     => $response['valid'],
            'status'    => $response['status'],
            'timestamp' => $response['timestamp'],
        ];
    }

    // --------------------
    // FRUITS
    // --------------------

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
