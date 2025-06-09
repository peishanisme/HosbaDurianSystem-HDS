<?php

namespace App\Livewire\Components;

use App\Models\Tree;
use Livewire\Component;

class TreeDetailsHeader extends Component
{
    public Tree $tree;
    public function render()
    {
        return view('livewire.components.tree-details-header');
    }
}
