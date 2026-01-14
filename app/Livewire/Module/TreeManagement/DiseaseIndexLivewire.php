<?php

namespace App\Livewire\Module\TreeManagement;

use Livewire\Component;

class DiseaseIndexLivewire extends Component
{
    public function render()
    {
        return view('livewire.module.tree-management.disease-index-livewire')->title(__('messages.disease_listing'));
    }
}
