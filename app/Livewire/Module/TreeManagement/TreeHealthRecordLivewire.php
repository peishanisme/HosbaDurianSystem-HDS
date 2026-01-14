<?php

namespace App\Livewire\Module\TreeManagement;

use App\Models\Tree;
use App\Traits\AuthorizesRoleOrPermission;
use Livewire\Component;
use Livewire\Attributes\Title;

#[Title('Tree Health Record')]
class TreeHealthRecordLivewire extends Component
{
    use AuthorizesRoleOrPermission;
    public Tree $tree;
    public function mount(): void
    {
        $this->authorizeRoleOrPermission(['view-health-record']);
    }
    public function render()
    {
        return view('livewire.module.tree-management.tree-health-record-livewire');
    }
}
