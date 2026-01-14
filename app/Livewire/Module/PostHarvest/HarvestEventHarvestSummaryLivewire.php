<?php

namespace App\Livewire\Module\PostHarvest;

use Livewire\Component;
use App\Models\HarvestEvent;
use Livewire\Attributes\Title;
use App\Traits\AuthorizesRoleOrPermission;

class HarvestEventHarvestSummaryLivewire extends Component
{
    use AuthorizesRoleOrPermission;
    public HarvestEvent $harvestEvent;


    public function mount(): void
    {
        $this->authorizeRoleOrPermission(['view-harvest-event']);
    }
    public function render()
    {
        return view('livewire.module.post-harvest.harvest-event-harvest-summary-livewire')->title(__('messages.harvest_event_harvest_summary'));
    }
}
