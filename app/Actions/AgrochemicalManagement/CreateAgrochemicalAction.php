<?php

namespace App\Actions\AgrochemicalManagement;

use App\DataTransferObject\AgrochemicalDTO;
use App\Models\Agrochemical;

class CreateAgrochemicalAction
{
    public function handle(AgrochemicalDTO $dto): Agrochemical
    {
        return Agrochemical::create($dto->toArray());
    }
}
