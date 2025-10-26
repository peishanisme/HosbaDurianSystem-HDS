<?php

namespace App\Livewire\Module\PostHarvest;

use App\Models\HarvestEvent;
use Livewire\Component;
use Livewire\Attributes\Title;

#[Title('Harvest Events')]
class HarvestEventHarvestSummaryLivewire extends Component
{
    public HarvestEvent $harvestEvent;
    public function render()
    {
        return view('livewire.module.post-harvest.harvest-event-harvest-summary-livewire');
    }
}
