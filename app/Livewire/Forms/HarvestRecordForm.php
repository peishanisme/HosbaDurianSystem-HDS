<?php

namespace App\Livewire\Forms;

use App\Models\HarvestRecord;
use Livewire\Attributes\Validate;
use Livewire\Form;

class HarvestRecordForm extends Form
{
    #[Validate('required', 'exists:harvest_records,uuid')]
    public string $harvest_uuid = '';

    #[Validate('required', 'exists:trees,uuid')]
    public string $tree_uuid = '';

    #[Validate('required')]
    public string $harvest_date = '';

    #[Validate('nullable', 'numeric', 'min:0')]
    public float $weight = 0;

    #[Validate('nullable', 'numeric', 'min:0')]
    public int $num_of_fruits = 0;

    #[Validate('required')]
    public bool $spoilt = false;

    public function edit(HarvestRecord $record)
    {
        $this->harvest_uuid = $record->harvest_uuid;
        $this->tree_uuid = $record->tree_uuid;
        $this->harvest_date = $record->harvest_date->format('Y-m-d');
        $this->weight = $record->weight ?? 0;
        $this->num_of_fruits = $record->num_of_fruits ?? 0;
        $this->spoilt = $record->spoilt;
    }

    public function update(HarvestRecord $record)
    {
        $record->update([
            'tree_uuid' => $this->tree_uuid,
            'harvest_date' => $this->harvest_date,
            'weight' => $this->weight,
            'num_of_fruits' => $this->num_of_fruits,
            'spoilt' => $this->spoilt,
        ]);
    }
}
