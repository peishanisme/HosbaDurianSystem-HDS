<?php

namespace App\Actions\MediaActions;

use App\Services\MediaService;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;

class UpdateMediaInStorage
{
    public function __construct(protected MediaService $mediaService) {}

    public function handle($object, $dto, $path)
    {
        $newThumbnail = $dto->thumbnail;
        $oldThumbnail = $object->thumbnail;

        if (empty($newThumbnail)) {
            return null;
        }

        if ($dto->thumbnail instanceof TemporaryUploadedFile) {
            $newThumbnailPath = $path . "/" . $dto->thumbnail->getClientOriginalExtension() . "/" . $dto->thumbnail->getFilename();

            // If the uploaded file would map to the same storage path, keep the old reference.
            if ($oldThumbnail === $newThumbnailPath) {
            return $oldThumbnail;
            }

            $thumbnailPath = $this->mediaService->put(
            $dto->thumbnail,
            $path . "/" . $dto->thumbnail->getClientOriginalExtension()
            );

            return $thumbnailPath;
        }
    }
}
