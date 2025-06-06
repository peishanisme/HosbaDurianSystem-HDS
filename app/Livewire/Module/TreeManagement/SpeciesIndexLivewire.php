<?php

namespace App\Livewire\Module\TreeManagement;

use Livewire\Component;
use Livewire\Attributes\Title;

#[Title('Tree Management')]
class SpeciesIndexLivewire extends Component
{
    public function render()
    {
        return view('livewire.tree-management.species-index-livewire');
    }
}
