<?php

namespace App\Actions\TreeManagement;

use App\Actions\MediaActions\SaveMediaToStorageAction;
use App\Models\Tree;
use Illuminate\Support\Facades\DB;
use App\DataTransferObject\TreeDTO;
use App\Services\MediaService;

class CreateTreeAction
{
    public function handle(TreeDTO $dto): Tree
    {
        return DB::transaction(function () use ($dto) {

            $thumbnailPath = (new SaveMediaToStorageAction(app(MediaService::class)))->handle($dto, 'trees');

            $tree = Tree::create([
                'species_id'        => $dto->species_id,
                'planted_at'        => $dto->planted_at,
                'thumbnail'         => $thumbnailPath,
                'latitude'          => $dto->latitude,
                'longitude'         => $dto->longitude,
                'flowering_period'  => $dto->flowering_period,
            ]);

            $tree->growthLogs()->create([
                'height'   => $dto->height,
                'diameter' => $dto->diameter,
            ]);

            return $tree;
        });
    }
}
