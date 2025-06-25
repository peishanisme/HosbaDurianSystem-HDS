<?php

namespace App\Livewire\Forms;

use Livewire\Form;
use App\Models\Species;
use Illuminate\Validation\Rule;
use App\DataTransferObject\SpeciesDTO;
use App\Actions\TreeManagement\CreateSpeciesAction;
use App\Actions\TreeManagement\UpdateSpeciesAction;

class SpeciesForm extends Form
{
    public ?Species $species = null;
    public ?string $name, $description, $code;

    protected function rules(): array
    {
        return [
            'name'        => ['required', 'string', 'max:255'],
            'code' => [
                'required',
                'string',
                'max:50',
                Rule::unique('species', 'code')
                    ->ignore($this->species->id ?? null)
                    ->whereNull('deleted_at'),
            ],
            'description' => ['nullable', 'string', 'max:255'],
        ];
    }

    public function edit(Species $species): void
    {
        $this->species = $species;
        $this->name = $species->name;
        $this->code = $species->code;
        $this->description = $species->description;
    }

    public function create(array $validatedData): void
    {
        app(CreateSpeciesAction::class)->handle(SpeciesDTO::fromArray($validatedData));
    }

    public function update($validatedData): void
    {
        app(UpdateSpeciesAction::class)->handle($this->species, SpeciesDTO::fromArray($validatedData));
    }
}
