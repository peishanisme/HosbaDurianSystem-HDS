<?php

namespace App\Livewire\Module\TreeManagement;

use App\Models\Tree;
use Livewire\Component;

class TreeFeedbackLivewire extends Component
{
    public Tree $tree;
    
    public function render()
    {
        return view('livewire.module.tree-management.tree-feedback-livewire')->title(__('messages.tree_feedbacks'));
    }
}
