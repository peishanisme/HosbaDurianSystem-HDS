<?php

namespace App\Livewire\Forms;

use App\Models\Species;
use Livewire\Attributes\Validate;
use Livewire\Form;

class SpeciesForm extends Form
{
    public ?Species $species = null;
    public ?string $name, $description;
    public bool $is_active = true;

    protected function rules(): array
    {
        return [
            'name'        => ['required', 'string', 'max:255', 'unique:species,name,' . ($this->species->id ?? 'NULL')],
            'description' => ['nullable', 'string', 'max:255'],
            'is_active'   => ['required', 'boolean'],
        ];
    }

    public function edit(Species $species): void
    {
        $this->species = $species;
        $this->name = $species->name;
        $this->description = $species->description;
        $this->is_active = $species->is_active;
    }
    public function create(array $validatedData): void
    {
        Species::create($validatedData);
    }
    
    public function update(array $validatedData): void
    {
        $this->species->update($validatedData);
    }
}
