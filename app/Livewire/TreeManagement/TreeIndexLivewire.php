<?php

namespace App\Livewire\TreeManagement;

use Livewire\Component;
use Livewire\Attributes\Title;

#[Title('Tree Management')]
class TreeIndexLivewire extends Component
{
    public function render()
    {
        return view('livewire.tree-management.tree-index-livewire');
    }
}
