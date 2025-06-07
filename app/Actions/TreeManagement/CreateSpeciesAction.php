<?php

namespace App\Actions\TreeManagement;

use App\DataTransferObject\SpeciesDTO;
use App\Models\Species;

class CreateSpeciesAction
{
    public function handle(SpeciesDTO $dto): Species
    {
        return Species::create($dto->toArray());
    }
}
