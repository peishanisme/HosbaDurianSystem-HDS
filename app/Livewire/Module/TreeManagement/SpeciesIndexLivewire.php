<?php

namespace App\Livewire\Module\TreeManagement;

use App\Models\Species;
use Livewire\Component;
use App\Traits\SweetAlert;
use Livewire\Attributes\On;
use Livewire\Attributes\Title;
use App\Traits\AuthorizesRoleOrPermission;

#[Title('Tree Management')]
class SpeciesIndexLivewire extends Component
{
    use SweetAlert,AuthorizesRoleOrPermission;

    public Species $species;

    public function mount(): void
    {
        $this->authorizeRoleOrPermission(['view-species']);
    }

    #[On('delete-species')]
    public function deleteSpecies(Species $species)
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
        return view('livewire.module.tree-management.species-index-livewire');
    }
}
