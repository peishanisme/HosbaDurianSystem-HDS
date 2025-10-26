<?php

namespace App\Actions\TreeManagement;

use App\Models\Tree;
use App\Services\MediaService;
use Illuminate\Support\Facades\DB;
use App\DataTransferObject\TreeDTO;
use App\Actions\MediaActions\UpdateMediaInStorage;

class UpdateTreeAction
{
    public function handle(Tree $tree, TreeDTO $dto): Tree
    {
        return DB::transaction(function () use ($tree, $dto) {

            $thumbnailPath = (new UpdateMediaInStorage(app(MediaService::class)))->handle($tree, $dto, 'trees');
            $tree->update([
                'species_id' => $dto->species_id,
                'planted_at' => $dto->planted_at,
                'thumbnail'  => $thumbnailPath,
                'flowering_period' => $dto->flowering_period,
            ]);

            $firstGrowthLog = $tree->growthLogs()->orderBy('id')->first();
            if ($firstGrowthLog) {
                $firstGrowthLog->update([
                    'height' => $dto->height,
                    'diameter' => $dto->diameter,
                ]);
            } else {
                $tree->growthLogs()->create([
                    'height' => $dto->height,
                    'diameter' => $dto->diameter,
                ]);
            }

            return $tree->fresh();
        });
    }
}
