<?php

namespace App\Actions\TreeManagement\Health;

use App\DataTransferObject\DiseaseDTO;
use App\Models\Disease;

class UpdateDiseaseAction
{
    public function handle(Disease $disease, DiseaseDTO $dto): Disease
    {
        return tap($disease)->update($dto->toArray());
    }
}
