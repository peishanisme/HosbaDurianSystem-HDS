<?php

namespace App\Actions\PostHarvest;

use App\Models\HarvestGrade;
use App\DataTransferObject\HarvestGradeDTO;

class CreateHarvestGradeAction
{
    public function handle(HarvestGradeDTO $dto): HarvestGrade
    {
        return HarvestGrade::create($dto->toArray());
    }
}