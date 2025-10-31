<?php

namespace App\Actions\AgrochemicalManagement;

use App\Models\Agrochemical;
use App\Services\MediaService;
use App\DataTransferObject\AgrochemicalDTO;
use App\Actions\MediaActions\UpdateMediaInStorage;

class UpdateAgrochemicalAction
{
    public function handle(Agrochemical $agrochemical, AgrochemicalDTO $dto): Agrochemical
    {
        $thumbnailPath = (new UpdateMediaInStorage(app(MediaService::class)))->handle($agrochemical, $dto, 'agrochemicals');
        $agrochemical->update([
            'name'                      => $dto->name,
            'quantity_per_unit'         => $dto->quantity_per_unit,
            'price'                     => $dto->price,
            'type'                      => $dto->type,
            'description'               => $dto->description,
            'thumbnail'                 => $thumbnailPath,
        ]);
        
        return $agrochemical->fresh();
    }
}
