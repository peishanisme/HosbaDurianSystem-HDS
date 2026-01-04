<?php

namespace App\Livewire\Components\Headers;

use App\Models\Tree;
use Livewire\Component;

class TreeDetailsHeader extends Component
{
    public Tree $tree;
    protected $listeners = ['refreshComponent' => '$refresh'];

    
    public function render()
    {
        return view('livewire.components.headers.tree-details-header');
    }
}
