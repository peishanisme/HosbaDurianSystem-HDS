<?php

namespace App\Livewire\Module\TreeManagement;

use App\Models\Species;
use Livewire\Component;
use App\Traits\SweetAlert;
use Livewire\Attributes\On;
use Livewire\Attributes\Title;

#[Title('Tree Management')]
class SpeciesIndexLivewire extends Component
{
    use SweetAlert;

    public Species $species;

    #[On('delete-species')]
    public function deleteTree(Species $species)
    {
        $this->species = $species;
        $this->alertConfirm('Are you sure you want to delete this species?', 'confirm-delete');
    }

    #[On('confirm-delete')]
    public function confirmDelete()
    {
        $this->species->delete();
        $this->alertSuccess('Species deleted successfully.');
    }
    
    public function render()
    {
        return view('livewire.tree-management.species-index-livewire');
    }
}
