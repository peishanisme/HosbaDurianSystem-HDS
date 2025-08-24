<?php

namespace App\Actions\TreeManagement\Health;

use App\DataTransferObject\DiseaseDTO;
use App\Models\Species;

class CreateDiseaseAction
{
    public function handle(DiseaseDTO $dto): Disease
    {
        return Disease::create($dto->toArray());
    }
}
