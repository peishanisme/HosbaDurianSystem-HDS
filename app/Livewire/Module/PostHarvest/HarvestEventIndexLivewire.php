<?php

namespace App\Livewire\Module\PostHarvest;

use App\Traits\SweetAlert;
use Livewire\Component;
use Livewire\Attributes\Title;

#[Title('Harvest Events')]
class HarvestEventIndexLivewire extends Component
{
    use SweetAlert;

    

    public function render()
    {
        return view('livewire.module.post-harvest.harvest-event-index-livewire');
    }
}
