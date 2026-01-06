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

    public ?HarvestEvent $harvestEvent = null;
    public HarvestEventForm $form;

    /**
     * create | edit
     */
    public string $mode = 'create';

    public string $modalID = 'harvestEventModalLivewire';
    public string $modalTitle = 'Harvest Event Details';

    #[On('reset-harvest-event')]
    public function resetInput(): void
    {
        $this->mode = 'create';
        $this->harvestEvent = null;

        $this->form->resetValidation();
        $this->form->reset();
    }

    #[On('edit-harvest-event')]
    public function edit(HarvestEvent $harvestEvent): void
    {
        $this->mode = 'edit';
        $this->harvestEvent = $harvestEvent;

        $this->form->resetValidation();
        $this->form->edit($harvestEvent);
    }

    /**
     * Derived state (NO flicker)
     */
    public function hasUnclosedEvent(): bool
    {
        // Editing should NEVER be blocked
        if ($this->mode === 'edit') {
            return false;
        }

        return HarvestEvent::whereNull('end_date')->exists();
    }

    public function create(): void
    {
        // Extra safety: block create even if UI fails
        if ($this->hasUnclosedEvent()) {
            $this->alertError(
                'There is an unclosed harvest event. Please close it before creating a new one.',
                $this->modalID
            );
            return;
        }

        $validatedData = $this->form->validate();

        try {
            $this->form->create($validatedData);
            $this->alertSuccess(
                'Harvest event has been created successfully.',
                $this->modalID
            );
        } catch (Exception $error) {
            $this->alertError($error->getMessage(), $this->modalID);
        }
    }

    public function update(): void
    {
        $validatedData = $this->form->validate();

        try {
            $this->form->update($validatedData);
            $this->alertSuccess(
                'Harvest event has been updated successfully.',
                $this->modalID
            );
        } catch (Exception $error) {
            $this->alertError($error->getMessage(), $this->modalID);
        }
    }

    public function render()
    {
        return view('livewire.module.post-harvest.harvest-event-modal-livewire');
    }
}
