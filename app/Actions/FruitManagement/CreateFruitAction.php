<?php

namespace App\Actions\FruitManagement;

use App\Models\Fruit;
use Illuminate\Support\Facades\DB;
use App\DataTransferObject\FruitDTO;

class CreateFruitAction
{
    public function handle(FruitDTO $dto): Fruit
    {
        return DB::transaction(function () use ($dto) {
            return Fruit::create($dto->toArray());
        });
    }
}
