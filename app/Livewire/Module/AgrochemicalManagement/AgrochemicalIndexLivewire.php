<?php

namespace App\Livewire\Module\AgrochemicalManagement;

use Livewire\Component;
use App\Traits\SweetAlert;
use Livewire\Attributes\On;
use App\Models\Agrochemical;
use Livewire\Attributes\Title;
use App\Traits\AuthorizesRoleOrPermission;

#[Title('Agrochemicals Management')]
class AgrochemicalIndexLivewire extends Component
{
    use SweetAlert, AuthorizesRoleOrPermission;
    public Agrochemical $agrochemical;

    #[On('delete-agrochemical')]
    public function deleteTree(Agrochemical $agrochemical)
    {
        $this->agrochemical = $agrochemical;
        $this->alertConfirm('Are you sure you want to delete this agrochemical?', 'confirm-delete');
    }

    #[On('confirm-delete')]
    public function confirmDelete()
    {
        $this->agrochemical->delete();
        $this->alertSuccess('Agrochemical deleted successfully.');
    }
    
    public function render()
    {
        return view('livewire.module.agrochemical-management.agrochemical-index-livewire');
    }
}
