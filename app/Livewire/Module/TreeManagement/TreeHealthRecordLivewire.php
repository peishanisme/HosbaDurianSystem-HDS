<?php

namespace App\Livewire\Module\TreeManagement;

use App\Models\Tree;
use Livewire\Component;
use Livewire\Attributes\Title;

#[Title('Tree Health Record')]
class TreeHealthRecordLivewire extends Component
{
    public Tree $tree;
    public function render()
    {
        return view('livewire.module.tree-management.tree-health-record-livewire');
    }
}
