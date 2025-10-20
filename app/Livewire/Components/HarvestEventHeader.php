<?php

namespace App\Livewire\Components;

use Livewire\Component;
use App\Models\HarvestEvent;

class HarvestEventHeader extends Component
{
    protected $listeners = ['refreshComponent' => '$refresh'];

    public HarvestEvent $harvestEvent;

    public function render()
    {
        return view('livewire.components.harvest-event-header');
    }
}
