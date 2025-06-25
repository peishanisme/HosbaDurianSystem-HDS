<?php

namespace App\Livewire\Module\TreeManagement;

use App\Models\Tree;
use App\Traits\AuthorizesRoleOrPermission;
use App\Traits\SweetAlert;
use Livewire\Component;
use Livewire\Attributes\On;
use Livewire\Attributes\Title;

#[Title('Tree Management')]
class TreeIndexLivewire extends Component
{
    use SweetAlert, AuthorizesRoleOrPermission;
    public Tree $tree;

    public function mount(): void
    {
        $this->authorizeRoleOrPermission(['view-tree']);
    }
    
    #[On('delete-tree')]
    public function deleteTree(Tree $tree)
    {
        $this->tree = $tree;
        $this->alertConfirm('Are you sure you want to delete this tree?', 'confirm-delete');
    }

    #[On('confirm-delete')]
    public function confirmDelete()
    {
        $this->tree->delete();
        $this->alertSuccess('Tree deleted successfully.');
    }

    public function render()
    {
        return view('livewire.module.tree-management.tree-index-livewire');
    }
}
