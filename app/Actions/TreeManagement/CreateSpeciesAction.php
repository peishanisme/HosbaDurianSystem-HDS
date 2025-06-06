<?php

namespace App\Actions\UserManagement;

use App\DataTransferObject\SpeciesDTO;
use App\Models\Species;

class CreateSpeciesAction
{
    public function handle(SpeciesDTO $dto): Species
    {
        return Species::create([
            'name' => $dto->name,
            'description' => $dto->description,
            'is_active' => $dto->is_active,
        ]);
    }
}
