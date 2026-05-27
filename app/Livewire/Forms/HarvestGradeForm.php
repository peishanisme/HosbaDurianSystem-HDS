<?php

namespace App\Livewire\Forms;

use App\Models\HarvestGrade;
use Livewire\Attributes\Validate;
use Livewire\Form;

class HarvestGradeForm extends Form
{
    #[Validate('required')]
    public ?string $date = '';

    #[Validate('nullable', 'numeric', 'min:0')]
    public ?float $weight = 0;

    #[Validate('nullable')]
    public ?string $grade = '';

    #[Validate('nullable', 'exists:species,id')]
    public ?int $species_id = 0;

    public function edit(HarvestGrade $record)
    {
        $this->date = $record->date ? $record->date->format('Y-m-d') : null;
        $this->weight = $record->weight ?? null;
        $this->grade = $record->grade ?? null;
        $this->species_id = $record->species_id ?? null;
    }

    public function update(HarvestGrade $record)
    {
        $record->update([
            'date' => $this->date,
            'weight' => $this->weight,
            'grade' => $this->grade,
            'species_id' => $this->species_id,
        ]);
    }
}
