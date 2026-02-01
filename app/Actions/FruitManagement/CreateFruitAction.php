<?php

namespace App\Actions\FruitManagement;

use App\Models\Fruit;
use App\Services\PinataService;
use App\Jobs\SyncFruitToPinataJob;
use Illuminate\Support\Facades\DB;
use App\Services\BlockchainService;
use App\DataTransferObject\FruitDTO;
use App\Traits\FruitBlockchainHelper;

class CreateFruitAction
{
    use FruitBlockchainHelper;

    public function __construct(protected PinataService $pinataService, protected BlockchainService $blockchainService) {}

    public function handle(FruitDTO $dto): Fruit
    {
        $fruit = DB::transaction(fn() => Fruit::create($dto->toArray()));

        return $fruit->fresh();
    }
}
