<?php

namespace App\Actions\MediaActions;

use App\Services\MediaService;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;

class SaveMediaToStorageAction
{
    public function __construct(protected MediaService $mediaService) {}

    public function handle($dto, $path): string
    {
        $thumbnailPath = null;

        if ($dto->thumbnail instanceof TemporaryUploadedFile) {
            $thumbnailPath = $this->mediaService->put(
                $dto->thumbnail,
                $path . "/" . $dto->thumbnail->getClientOriginalExtension()
            );
        } elseif (is_string($dto->thumbnail)) {
            $thumbnailPath = $dto->thumbnail;
        }

        return $thumbnailPath;
    }
}
