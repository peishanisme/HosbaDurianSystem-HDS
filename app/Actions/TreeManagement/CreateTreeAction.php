<?php

namespace App\Actions\TreeManagement;

use App\Models\Tree;
use Illuminate\Support\Facades\DB;
use App\DataTransferObject\TreeDTO;
use App\Services\MediaService;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;

class CreateTreeAction
{
    public function __construct(protected MediaService $mediaService) {}

    public function handle(TreeDTO $dto): Tree
    {
        return DB::transaction(function () use ($dto) {
            $thumbnailPath = null;

            if ($dto->thumbnail instanceof TemporaryUploadedFile) {
                $thumbnailPath = $this->mediaService->put(
                    $dto->thumbnail,
                    "trees/" . $dto->thumbnail->getClientOriginalExtension()
                );
            } elseif (is_string($dto->thumbnail)) {
                // Already stored path
                $thumbnailPath = $dto->thumbnail;
            }

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
