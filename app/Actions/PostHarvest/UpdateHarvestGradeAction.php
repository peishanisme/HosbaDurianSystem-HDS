<?php

namespace App\Actions\PostHarvest;

use App\Models\HarvestGrade;
use App\DataTransferObject\HarvestGradeDTO;

class UpdateHarvestGradeAction
{
    public function handle(HarvestGrade $harvestGrade, HarvestGradeDTO $dto): HarvestGrade
    {
        return tap($harvestGrade)->update($dto->toArray());
    }
}