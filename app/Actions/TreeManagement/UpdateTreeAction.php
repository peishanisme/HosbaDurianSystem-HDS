<?php

namespace App\Actions\TreeManagement;

use App\Models\Tree;
use Illuminate\Support\Facades\DB;
use App\DataTransferObject\TreeDTO;

class UpdateTreeAction
{
    public function handle(Tree $tree, TreeDTO $dto): Tree
    {
        return DB::transaction(function () use ($tree, $dto) {
            $tree->update([
                'species_id' => $dto->species_id,
                'planted_at' => $dto->planted_at,
                'thumbnail'  => $dto->thumbnail,
                'flowering_period' => $dto->flowering_period,
            ]);

            $firstGrowthLog = $tree->growthLogs()->orderBy('id')->first();
            if ($firstGrowthLog) {
                $firstGrowthLog->update([
                    'height' => $dto->height,
                    'diameter' => $dto->diameter,
                ]);
            }

            return $tree->fresh();
        });
    }
}
