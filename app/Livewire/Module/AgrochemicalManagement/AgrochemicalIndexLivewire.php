<?php

namespace App\Livewire\Module\AgrochemicalManagement;

use Livewire\Component;
use App\Traits\SweetAlert;
use Livewire\Attributes\On;
use App\Models\Agrochemical;
use Livewire\Attributes\Title;
use App\Traits\AuthorizesRoleOrPermission;

class AgrochemicalIndexLivewire extends Component
{
    use SweetAlert, AuthorizesRoleOrPermission;
    public Agrochemical $agrochemical;
    
    public function mount(): void
    {
        $this->authorizeRoleOrPermission(['view-fertilizer-pesticide']);
    }

    #[On('delete-agrochemical')]
    public function deleteTree(Agrochemical $agrochemical)
    {
        $this->agrochemical = $agrochemical;
        $this->alertConfirm(__('messages.are_you_sure_delete_agrochemical'), 'confirm-delete');
    }

    #[On('confirm-delete')]
    public function confirmDelete()
    {
        $this->agrochemical->delete();
        $this->alertSuccess(__('messages.agrochemical_deleted_successfully'));
    }
    
    public function render()
    {
        return view('livewire.module.agrochemical-management.agrochemical-index-livewire')->title(__('messages.agrochemical_listing'));
    }
}
