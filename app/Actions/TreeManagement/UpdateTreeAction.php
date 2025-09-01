<?php

namespace App\Actions\TreeManagement;

use App\Models\Tree;
use App\Services\MediaService;
use Illuminate\Support\Facades\DB;
use App\DataTransferObject\TreeDTO;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;

class UpdateTreeAction
{
    public function __construct(protected MediaService $mediaService) {}

    public function handle(Tree $tree, TreeDTO $dto): Tree
    {
        return DB::transaction(function () use ($tree, $dto) {
            $thumbnailPath = $tree->thumbnail; 

            if ($dto->thumbnail instanceof TemporaryUploadedFile) {
                // delete old if exists
                if ($tree->thumbnail) {
                    $this->mediaService->delete($tree->thumbnail);
                }

                // upload new one
                $thumbnailPath = $this->mediaService->put(
                    $dto->thumbnail,
                    "trees/" . $dto->thumbnail->getClientOriginalExtension()
                );
            } elseif ($dto->thumbnail === null) {
                // handle removal
                if ($tree->thumbnail) {
                    $this->mediaService->delete($tree->thumbnail);
                }
                $thumbnailPath = null;
            }
            // else: if string, just keep it as-is

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
            }

            return $tree->fresh();
        });
    }
}
