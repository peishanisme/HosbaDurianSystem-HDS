<?php

namespace App\Actions\AgrochemicalManagement;

use App\DataTransferObject\AgrochemicalDTO;
use App\Models\Agrochemical;

class UpdateAgrochemicalAction
{
    public function handle(Agrochemical $agrochemical, AgrochemicalDTO $dto): Agrochemical
    {
        return tap($agrochemical)->update($dto->toArray());
    }
}
