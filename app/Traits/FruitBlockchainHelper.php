<?php
namespace App\Traits;

use App\Models\Fruit;
use App\Services\PinataService;
use App\Services\BlockchainService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

trait FruitBlockchainHelper
{
    protected function buildMetadata(Fruit $fruit): array
    {
        $fruit->load(['tree.species', 'harvestEvent']);
        return [
            'id' => $fruit->fruit_tag,
            'date' => $fruit->harvested_at,
            'weight' => "{$fruit->weight} kg",
            'grade' => $fruit->grade,
            'species' => $fruit->tree->species->name ?? 'Unknown',
            'tree_origin' => $fruit->tree->tree_tag ?? $fruit->tree_uuid,
            'harvest_event' => $fruit->harvestEvent->event_name ?? null,
            'version' => $fruit->version,
        ];
    }

    protected function computeMetadataHash(array $metadata): string
    {
        return hash('sha256', json_encode($metadata, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE));
    }

    protected function uploadToPinata(array $metadata, string $fruitTag, PinataService $pinataService): string
    {
        try {
            $ipfs = $pinataService->uploadJson($metadata, $fruitTag);
            if (empty($ipfs['IpfsHash'])) {
                throw new \RuntimeException('Pinata upload failed: missing IpfsHash');
            }
            return $ipfs['IpfsHash'];
        } catch (\Throwable $e) {
            Log::error('Pinata upload exception', ['error' => $e->getMessage(), 'fruit_tag' => $fruitTag]);
            throw $e;
        }
    }

    protected function saveMetadata(Fruit $fruit, string $cid, string $hash)
    {
        DB::transaction(function () use ($fruit, $cid, $hash) {
            $fruit->forceFill([
                'metadata_cid' => $cid,
                'metadata_hash' => $hash,
            ])->save();
        });
    }

    protected function pushToBlockchain(Fruit $fruit, string $metadataHash, BlockchainService $blockchainService)
    {
        try {
            $fruitId = ($fruit->version === 1) ? $fruit->fruit_tag : $fruit->fruit_tag . '-v' . $fruit->version;
        
            $payload = [
                'fruitId' => $fruitId,
                'metadataHash' => '0x' . $metadataHash,
            ];
            $result = $blockchainService->createFruit($payload); // or updateFruit if smart contract allows
            if ($result['success']) {
                DB::transaction(function () use ($fruit, $result) {
                    $fruit->forceFill([
                        'tx_hash' => $result['txHash'],
                        'is_onchain' => true,
                        'onchain_at' => now(),
                    ])->save();
                });
            } else {
                Log::warning('Blockchain push failed', ['fruit_id' => $fruit->id, 'payload' => $payload]);
            }
        } catch (\Throwable $e) {
            Log::error('Blockchain push exception', ['error' => $e->getMessage(), 'fruit_id' => $fruit->id]);
        }
    }
}
