<?php

namespace App\Actions\MediaActions;

use App\Services\MediaService;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;

class UpdateMediaInStorage
{
    public function __construct(protected MediaService $mediaService) {}

    public function handle($object, $dto, $path): string
    {
        $thumbnailPath = $object->thumbnail; 

        if ($dto->thumbnail instanceof TemporaryUploadedFile) {
            // delete old if exists
            if ($object->thumbnail) {
                $this->mediaService->delete($object->thumbnail);
                }

                // upload new one
                $thumbnailPath = $this->mediaService->put(
                    $dto->thumbnail,
                    $path . "/" . $dto->thumbnail->getClientOriginalExtension()
                );
            } elseif ($dto->thumbnail === null) {
                // handle removal
                if ($object->thumbnail) {
                    $this->mediaService->delete($object->thumbnail);
                }
                $thumbnailPath = null;
            }

            return $thumbnailPath;
            // else: if string, just keep it as-is
    }
}
