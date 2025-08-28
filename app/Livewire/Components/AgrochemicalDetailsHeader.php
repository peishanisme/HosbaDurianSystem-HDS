<?php

namespace App\Livewire\Components;

use Livewire\Component;
use Livewire\Attributes\On;
use App\Models\Agrochemical;

class AgrochemicalDetailsHeader extends Component
{
    public Agrochemical $agrochemical;
    public $listeners = ['refresh-header' => '$refresh'];

    #[On('refresh-header')] 
    public function render()
    {
        return view('livewire.components.agrochemical-details-header');
    }
}
