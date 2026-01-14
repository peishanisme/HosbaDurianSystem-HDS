<?php

namespace App\Livewire\Module\AgrochemicalManagement;

use Exception;
use Livewire\Component;
use App\Traits\SweetAlert;
use Livewire\Attributes\On;
use App\Models\Agrochemical;
use App\Livewire\Forms\AgrochemicalStockMovementForm;
use App\Models\AgrochemicalStockMovement;

class AgrochemicalUpdateStockModalLivewire extends Component
{
    use SweetAlert;

    public string $modalID = 'agrochemicalStockMovementModalLivewire', $modalTitle;
    public AgrochemicalStockMovementForm $form;
    public ?Agrochemical $agrochemical = null;

    public function mount()
    {
        $this->modalTitle = __('messages.update_agrochemical_stock');
        $this->form->agrochemical = $this->agrochemical;
        $this->form->name = $this->agrochemical?->name;
        $this->form->agrochemical_uuid = $this->agrochemical?->uuid;
    }

    #[On('reset-stock')]
    public function resetInput()
    {
        // $this->form->resetValidation();
        // $this->form->reset();
    }

     #[On('edit-stock')]
    public function edit(AgrochemicalStockMovement $stock): void
    {
        $this->resetInput();
        $this->form->edit($stock);
    }

    public function create(): void
    {
        $validatedData = $this->form->validate();

        try {

            $this->form->create($validatedData);
            $this->alertSuccess(__('messages.inventory_created_successfully'), $this->modalID);
            
            $this->dispatch('refresh-header');

        } catch (Exception $error) {

            $this->alertError($error->getMessage(), $this->modalID);
        }
    }

    public function update(): void
    {
        $validatedData = $this->form->validate();

        try {

            $this->form->update($validatedData);
            $this->alertSuccess(__('messages.inventory_updated_successfully'), $this->modalID);
        } catch (Exception $error) {

            $this->alertError($error->getMessage(), $this->modalID);
        }
    }

    public function render()
    {
        return view('livewire.module.agrochemical-management.agrochemical-update-stock-modal-livewire');
    }
}
