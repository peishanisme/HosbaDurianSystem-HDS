<?php

namespace App\Actions\MediaActions;

use App\Services\MediaService;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;

class UpdateMediaInStorage
{
    public function __construct(protected MediaService $mediaService) {}

    public function handle($object, $dto, $path)
    {
        $thumbnailPath = $dto->thumbnail;
        $oldThumbnail = $object->thumbnail;

        // Handle removal
        if ($dto->thumbnail === null) {
            if ($oldThumbnail) {
                $this->mediaService->delete($oldThumbnail);
            }
            return null;
        }

        // Handle new upload
        if ($dto->thumbnail instanceof TemporaryUploadedFile) {
            if ($oldThumbnail) {
                $this->mediaService->delete($oldThumbnail);
            }
            $thumbnailPath = $this->mediaService->put(
                $dto->thumbnail,
                $path . "/" . $dto->thumbnail->getClientOriginalExtension()
            );
            return $thumbnailPath;
        }

        // Handle unchanged or string path
        $newThumbnailPath = $path . "/" . $dto->thumbnail->getClientOriginalExtension() . "/" . $dto->thumbnail->getFilename();
        if ($oldThumbnail === $newThumbnailPath) {
            return $thumbnailPath;
        }

        // Default: just return as-is
        return $thumbnailPath;
    }
}
