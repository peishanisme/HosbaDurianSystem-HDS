<?php

namespace App\Actions\PostHarvest;

use App\Models\HarvestEvent;
use App\DataTransferObject\HarvestEventDTO;

class CreateHarvestEventAction
{
    public function handle(HarvestEventDTO $dto): HarvestEvent
    {
        return HarvestEvent::create($dto->toArray());
    }
}
