<?php

namespace App\Livewire\Module\TreeManagement;

use App\Models\Tree;
use Livewire\Component;
use Livewire\Attributes\Title;

#[Title('Tree Management')]
class TreeDetailsLivewire extends Component
{
    public Tree $tree;
    public function render()
    {
        return view('livewire.module.tree-management.tree-details-livewire');
    }
}
