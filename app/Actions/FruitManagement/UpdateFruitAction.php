<?php

namespace App\Actions\FruitManagement;

use App\Models\Fruit;
use App\Services\PinataService;
use App\Jobs\SyncFruitToPinataJob;
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

        // SyncFruitToPinataJob::dispatch($fruit->id);

        return $fruit->fresh();
    }
}
