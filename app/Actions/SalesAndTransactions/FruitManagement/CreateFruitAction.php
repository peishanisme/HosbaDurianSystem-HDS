<?php

namespace App\Actions\FruitManagement;

use App\Models\Fruit;
use Illuminate\Support\Facades\DB;
use App\DataTransferObject\TreeDTO;

class CreateTreeAction
{
    public function handle(TreeDTO $dto): Tree
    {
        
         return DB::transaction(function () use ($dto) {
            $tree = Tree::create(
                [
                    'species_id' => $dto->species_id,
                    'planted_at' => $dto->planted_at,
                    'thumbnail'  => $dto->thumbnail,
                    'latitude'  => $dto->latitude,
                    'longitude' => $dto->longitude,
                    'flowering_period' => $dto->flowering_period,
                ]
            );

            $tree->growthLogs()->create([
                'height' => $dto->height,
                'diameter' => $dto->diameter,
            ]);

            return $tree;
        });
    }
}
