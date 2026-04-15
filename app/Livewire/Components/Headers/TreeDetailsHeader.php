<?php

namespace App\Livewire\Components\Headers;

use App\Models\Tree;
use Livewire\Component;

class TreeDetailsHeader extends Component
{
    public Tree $tree;
    public string $treeUrl = '';
    protected $listeners = ['refreshComponent' => '$refresh'];

    public function mount()
    {
        $this->treeUrl = route('public.portal', $this->tree->uuid);
    }


    public function render()
    {
        return view('livewire.components.headers.tree-details-header');
    }
}
