<?php

namespace App\Livewire\Module\AgrochemicalManagement;

use Livewire\Component;
use App\Traits\SweetAlert;
use Livewire\Attributes\On;
use App\Livewire\Forms\AgrochemicalStockMovementForm;

class AgrochemicalUpdateStockModalLivewire extends Component
{
    use SweetAlert;
    public string $modalID = 'agrochemicalStockMovementModalLivewire', $modalTitle = 'Agrochemical Stock Details';
    public AgrochemicalStockMovementForm $form;

    #[On('reset-stock')]
    public function resetInput()
    {
        // $this->form->resetValidation();
        // $this->form->reset();
        // $this->dispatch('reset-thumbnail');
    }
    
    public function render()
    {
        return view('livewire.module.agrochemical-management.agrochemical-update-stock-modal-livewire');
    }
}
