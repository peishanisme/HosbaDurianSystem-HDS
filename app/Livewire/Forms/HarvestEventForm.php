<?php

namespace App\Livewire\Forms;

use Livewire\Form;
use App\Models\HarvestEvent;
use Livewire\Attributes\Validate;
use App\DataTransferObject\HarvestEventDTO;
use App\Actions\PostHarvest\CreateHarvestEventAction;
use App\Actions\PostHarvest\UpdateHarvestEventAction;

class HarvestEventForm extends Form
{
    public ?string  $event_name, $start_date, $end_date, $description;
    public ?HarvestEvent $harvestEvent = null;

    public function rules(): array
    {
        return [
            'event_name' => ['required', 'string', 'max:255'],
            'start_date' => ['required', 'date', 'before_or_equal:today'],
            'end_date' => [
                'nullable',
                'date',
                function ($attribute, $value, $fail) {
                    if ($value !== null && isset($this->start_date) && $value < $this->start_date) {
                        $fail('The end date must be after or equal to the start date.');
                    }
                },
            ],
            'description' => ['nullable', 'string'],
        ];
    }

    public function edit(HarvestEvent $harvestEvent): void
    {
        $this->harvestEvent = $harvestEvent;
        $this->event_name = $harvestEvent->event_name;
        $this->start_date = $harvestEvent->start_date;
        $this->end_date = $harvestEvent->end_date;
        $this->description = $harvestEvent->description;
    }

    public function create(array $validatedData): void
    {
        app(CreateHarvestEventAction::class)->handle(HarvestEventDTO::fromArray($validatedData));
    }

    public function update($validatedData): void
    {
        app(UpdateHarvestEventAction::class)->handle($this->harvestEvent, HarvestEventDTO::fromArray($validatedData));
    }
}
