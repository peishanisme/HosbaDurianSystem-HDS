<?php

namespace App\Livewire\Components\Headers;

use Livewire\Component;
use App\Models\HarvestEvent;

class HarvestEventHeader extends Component
{
    protected $listeners = ['refreshComponent' => '$refresh'];

    public HarvestEvent $harvestEvent;

    public function render()
    {
        return view('livewire.components.headers.harvest-event-header');
    }
}
