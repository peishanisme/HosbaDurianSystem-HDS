<?php

namespace App\Livewire\Module\TreeManagement;

use Livewire\Component;
use Livewire\Attributes\Title;

#[Title('Tree Management')]
class DiseaseIndexLivewire extends Component
{
    public function render()
    {
        return view('livewire.module.tree-management.disease-index-livewire');
    }
}
