<?php

namespace App\Livewire\Components\Headers;

use Livewire\Component;
use Livewire\Attributes\On;
use App\Models\Agrochemical;

class AgrochemicalDetailsHeader extends Component
{
    public Agrochemical $agrochemical;
    protected $listeners = ['refreshComponent' => '$refresh'];

    public function render()
    {
        return view('livewire.components.headers.agrochemical-details-header');
    }
}
