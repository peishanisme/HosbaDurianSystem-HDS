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
                'area' => $dto->area ?? $tree->area,
                'terrace' => $dto->terrace ?? $tree->terrace,
                'water_valve' => $dto->water_valve ?? $tree->water_valve,
            ]);

            $firstGrowthLog = $tree->growthLogs()->orderBy('id')->first();
            if ($dto->height !== null || $dto->diameter !== null) {
                if ($firstGrowthLog) {
                    $firstGrowthLog->update([
                        'height' => $dto->height ?? $firstGrowthLog->height,
                        'diameter' => $dto->diameter ?? $firstGrowthLog->diameter,
                    ]);
                } else {
                    $tree->growthLogs()->create([
                        'height' => $dto->height ?? null,
                        'diameter' => $dto->diameter ?? null,
                    ]);
                }
            }

            return $tree->fresh();
        });
    }
}
