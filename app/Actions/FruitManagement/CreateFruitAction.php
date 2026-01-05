<?php

namespace App\Actions\FruitManagement;

use App\Models\Fruit;
use App\Services\PinataService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\DataTransferObject\FruitDTO;
use App\Services\BlockchainService;

class CreateFruitAction
{
    public function __construct(protected PinataService $pinataService, protected BlockchainService $blockchainService) {}

    public function handle(FruitDTO $dto): Fruit
    {
        // 1) Prepare minimal model data (DTO -> array)
        $baseData = $dto->toArray();

        // Create fruit record (core fields only)
        $fruit = DB::transaction(function () use ($baseData) {
            return Fruit::create($baseData);
        });

        // Load relations required for metadata
        $fruit->load(['tree.species', 'harvestEvent']);

        // Build metadata
        $metadata = [
            'id' => $fruit->fruit_tag,
            'date' => $fruit->harvested_at,
            'weight' => "{$fruit->weight} kg",
            'grade' => $fruit->grade,
            'species' => $fruit->tree->species->name ?? 'Unknown',
            'tree_origin' => $fruit->tree->tree_tag ?? $fruit->tree_uuid,
            'harvest_event' => $fruit->harvestEvent->event_name ?? null,
        ];

        // JSON and hash
        $metadataJson = json_encode($metadata, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
        $metadataHash = hash('sha256', $metadataJson);

        // 2) Upload to Pinata (outside DB transaction so network errors don't rollback DB)
        $ipfs = null;
        try {
            $ipfs = $this->pinataService->uploadJson($metadata, $fruit->fruit_tag);
            Log::info('Pinata upload response', $ipfs ?? []);
        } catch (\Throwable $e) {
            Log::error('Pinata upload exception', ['error' => $e->getMessage(), 'fruit_id' => $fruit->id]);
            // retry mechanism, dispatch a job here.
            // dispatch(new RetryPinataUploadJob($fruit, $metadata));
            throw $e;
        }

        if (empty($ipfs['IpfsHash'])) {
            Log::error('Pinata upload returned no IpfsHash', ['response' => $ipfs, 'fruit_id' => $fruit->id]);
            throw new \RuntimeException('Pinata upload failed: missing IpfsHash');
        }

        // 3) Save CID + hash in DB in a new transaction (safe and atomic DB write)
        DB::transaction(function () use ($fruit, $ipfs, $metadataHash) {
            // Use forceFill if you haven't added columns to $fillable
            $fruit->forceFill([
                'metadata_cid' => $ipfs['IpfsHash'],
                'metadata_hash' => $metadataHash,
            ])->save();
        });

        // 4) Push to blockchain (outside DB transaction)
        try {
            $payload = [
                'fruitId' => $fruit->fruit_tag,
                'metadataHash' => '0x' . $metadataHash, 
            ];

            $blockchainResult = $this->blockchainService->createFruit($payload);

            if ($blockchainResult['success']) {
                $txHash = $blockchainResult['txHash'];

                // Update DB with blockchain info
                DB::transaction(function () use ($fruit, $txHash) {
                    $fruit->forceFill([
                        'tx_hash' => $txHash,
                        'is_onchain' => true,
                        'onchain_at' => now(),
                    ])->save();
                });

                Log::info('Fruit synced to blockchain', [
                    'fruit_id' => $fruit->id,
                    'txHash' => $blockchainResult['txHash'],
                ]);
            } else {
                Log::warning('Blockchain sync failed for fruit', [
                    'fruit_id' => $fruit->id,
                    'payload' => $payload,
                ]);
            }
        } catch (\Throwable $e) {
            Log::error('Blockchain push exception', [
                'error' => $e->getMessage(),
                'fruit_id' => $fruit->id,
            ]);
        }

        return $fruit->fresh();
    }
}