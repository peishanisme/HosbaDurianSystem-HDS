<?php

namespace App\Actions\TreeManagement;

use App\Models\Tree;
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
                    'flowering_period' => $dto->flowering_period,
                ]
            );

            $tree->growthLogs()->create([
                'height' => $dto->height,
                'diameter' => $dto->diameter,
                'photo' => $dto->thumbnail,
            ]);

            return $tree;
        });
    }
}
