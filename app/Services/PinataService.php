<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class PinataService
{
    protected string $baseUrl;
    protected string $jwt;

    public function __construct()
    {
        $this->baseUrl = config('services.pinata.base_url', 'https://api.pinata.cloud');
        $this->jwt = config('services.pinata.jwt', '');

        if (!$this->jwt) {
            Log::warning('Pinata JWT not loaded from config. Pinata uploads will be skipped.');
        }
    }

    /**
     * Upload JSON metadata to IPFS via Pinata
     */
    public function uploadJson(array $data, string $group = 'Fruit'): ?array
    {
        // Skip upload if JWT is not configured
        if (!$this->jwt) {
            Log::warning('Pinata JWT not available. Skipping IPFS upload.');
            return null;
        }

        try {
            $metadata = [
                'pinataMetadata' => json_encode([
                    'name' => $data['id'] ?? 'fruit_metadata',
                    'keyvalues' => [
                        'group' => $group,
                    ],
                ]),
            ];

            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->jwt,
            ])
                ->attach(
                    'file',
                    json_encode($data, JSON_PRETTY_PRINT),
                    'metadata.json'
                )
                ->post("{$this->baseUrl}/pinning/pinFileToIPFS", $metadata);

            if ($response->successful()) {
                return $response->json();
            }

            Log::error('Pinata upload failed', ['response' => $response->body()]);
            return null;
            
        } catch (\Throwable $e) {
            Log::error('Pinata upload exception', ['error' => $e->getMessage()]);
            return null;
        }
    }
}
