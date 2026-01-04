<?php

namespace App\Livewire\Module\TreeManagement;

use App\Models\Tree;
use Livewire\Component;
use Livewire\Attributes\Title;

#[Title('Tree Growth Log')]
class TreeGrowthLogLivewire extends Component
{
    public Tree $tree;
    public function render()
    {
        return view('livewire.module.tree-management.tree-growth-log-livewire');
    }
}
