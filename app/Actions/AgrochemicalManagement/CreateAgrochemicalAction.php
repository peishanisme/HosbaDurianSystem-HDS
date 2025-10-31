<?php

namespace App\Actions\AgrochemicalManagement;

use App\Models\Agrochemical;
use App\Services\MediaService;
use App\DataTransferObject\AgrochemicalDTO;
use App\Actions\MediaActions\SaveMediaToStorageAction;

class CreateAgrochemicalAction
{
    public function handle(AgrochemicalDTO $dto): Agrochemical
    {
        $thumbnailPath = (new SaveMediaToStorageAction(app(MediaService::class)))->handle($dto, 'agrochemicals');

        return Agrochemical::create([
            'name'                      => $dto->name,
            'quantity_per_unit'         => $dto->quantity_per_unit,
            'price'                     => $dto->price,
            'type'                      => $dto->type,
            'description'               => $dto->description,
            'thumbnail'                 => $thumbnailPath,
        ]);
    }
}
