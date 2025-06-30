<?php

namespace App\Livewire\Components;

use App\Models\Agrochemical;
use Livewire\Component;

class AgrochemicalDetailsHeader extends Component
{
    public Agrochemical $agrochemical;
    protected $listeners = ['refreshComponent' => '$refresh'];

    public function render()
    {
        return view('livewire.components.agrochemical-details-header');
    }
}
