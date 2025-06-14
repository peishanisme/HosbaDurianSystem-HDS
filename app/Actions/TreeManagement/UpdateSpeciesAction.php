<?php

namespace App\Actions\TreeManagement;

use App\DataTransferObject\SpeciesDTO;
use App\Models\Species;

class UpdateSpeciesAction
{
    public function handle(Species $species, SpeciesDTO $dto): Species
    {
        return tap($species)->update($dto->toArray());
    }
}
