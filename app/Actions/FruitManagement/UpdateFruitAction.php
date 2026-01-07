<?php

namespace App\Actions\FruitManagement;

use App\Models\Fruit;
use App\Services\PinataService;
use Illuminate\Support\Facades\DB;
use App\Services\BlockchainService;
use Illuminate\Support\Facades\Log;
use App\DataTransferObject\FruitDTO;
use App\Traits\FruitBlockchainHelper;

class UpdateFruitAction
{
    use FruitBlockchainHelper;

    public function __construct(protected PinataService $pinataService, protected BlockchainService $blockchainService) {}

    public function handle(FruitDTO $dto, $uuid): Fruit
    {
        $fruit = Fruit::where('uuid', $uuid)->firstOrFail();
        DB::transaction(function () use ($fruit, $dto) {
            $fruit->forceFill(
                array_merge(
                    $dto->toArray(),
                    ['version' => $fruit->version + 1] 
                )
            )->save();
        });

        $metadata = $this->buildMetadata($fruit);
        $hash = $this->computeMetadataHash($metadata);
        $cid = $this->uploadToPinata($metadata, $fruit->fruit_tag, $this->pinataService);
        $this->saveMetadata($fruit, $cid, $hash);
        $this->pushToBlockchain($fruit, $hash, $this->blockchainService);

        return $fruit->fresh();
    }
}
