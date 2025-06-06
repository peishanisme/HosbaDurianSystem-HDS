<?php

namespace App\Actions\UserManagement;

use App\DataTransferObject\SpeciesDTO;
use App\Models\Species;

class UpdateSpeciesAction
{
    public function handle(Species $species, SpeciesDTO $dto): Species
    {
        return tap($species)->update($dto->toArray());
    }
}
