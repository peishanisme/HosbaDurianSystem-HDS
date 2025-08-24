<?php

namespace App\Actions\PostHarvest;

use App\Models\HarvestEvent;
use App\DataTransferObject\HarvestEventDTO;

class UpdateHarvestEventAction
{
    public function handle(HarvestEvent $harvestEvent, HarvestEventDTO $dto): HarvestEvent
    {
        return tap($harvestEvent)->update($dto->toArray());
    }
}
