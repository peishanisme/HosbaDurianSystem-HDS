<?php

namespace App\Livewire\Module\TreeManagement;

use App\Models\Tree;
use Livewire\Component;

class TreeFeedbackLivewire extends Component
{
    public Tree $tree;
    public $feedbacks;
    public $sortDirection = 'desc';

    public function mount()
    {
        $this->feedbacks = $this->tree->feedbacks()->orderBy('created_at', $this->sortDirection)->get();
    }

    public function sortByDate()
    {
        $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';

        $this->feedbacks = $this->tree
            ->feedbacks()
            ->orderBy('created_at', $this->sortDirection)
            ->get();
    }

    public function render()
    {
        return view('livewire.module.tree-management.tree-feedback-livewire')->title(__('messages.tree_feedbacks'));
    }
}
