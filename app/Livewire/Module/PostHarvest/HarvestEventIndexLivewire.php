<?php

namespace App\Livewire\Module\PostHarvest;

use Livewire\Component;
use App\Traits\SweetAlert;
use Livewire\Attributes\Title;
use App\Traits\AuthorizesRoleOrPermission;

#[Title('Harvest Events')]
class HarvestEventIndexLivewire extends Component
{
    use SweetAlert, AuthorizesRoleOrPermission;
    
    public function mount(): void
    {
        $this->authorizeRoleOrPermission(['view-harvest-event']);
    }

    public function render()
    {
        return view('livewire.module.post-harvest.harvest-event-index-livewire');
    }
}
