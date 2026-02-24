<?php

namespace App\Livewire\Module\TreeManagement;

use App\Models\Tree;
use Livewire\Component;
use Livewire\Attributes\Title;

class TreeGrowthLogLivewire extends Component
{
    public Tree $tree;
    public function render()
    {
        return view('livewire.module.tree-management.tree-growth-log-livewire')->title(__('messages.tree_growth_logs'));
    }
}
