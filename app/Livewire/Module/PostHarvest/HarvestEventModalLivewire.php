<?php

namespace App\Livewire\Module\PostHarvest;

use Exception;
use Livewire\Component;
use App\Traits\SweetAlert;
use Livewire\Attributes\On;
use App\Models\HarvestEvent;
use App\Livewire\Forms\HarvestEventForm;

class HarvestEventModalLivewire extends Component
{
    use SweetAlert;

    public HarvestEvent $harvestEvent;
    public HarvestEventForm $form;
    public string $modalID = 'harvestEventModalLivewire', $modalTitle = 'Harvest Event Details';

    #[On('reset-harvest-event')]
    public function resetInput()
    {
        $this->form->resetValidation();
        $this->form->reset();
    }

    #[On('edit-harvest-event')]
    public function edit(HarvestEvent $harvestEvent): void
    {
        $this->resetInput();
        $this->form->edit($harvestEvent);
    }

    public function create(): void
    {        
        $validatedData = $this->form->validate();
        
        try {

            $this->form->create($validatedData);
            $this->alertSuccess('Harvest event has been created successfully.', $this->modalID);

        
        } catch (Exception $error) {

            $this->alertError($error->getMessage(), $this->modalID);
        
        }
    }

    public function update(): void
    {
        $validatedData = $this->form->validate();
        
        try {

            $this->form->update($validatedData);
            $this->alertSuccess('Harvest event has been updated successfully.', $this->modalID);

        } catch (Exception $error) {

            $this->alertError($error->getMessage(), $this->modalID);
        
        }
    }

    public function render()
    {
        return view('livewire.module.post-harvest.harvest-event-modal-livewire');
    }
}
