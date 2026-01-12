<?php

namespace App\Livewire\Forms;

use Livewire\Form;
use App\Models\Agrochemical;
use App\DataTransferObject\AgrochemicalDTO;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;
use App\Actions\AgrochemicalManagement\CreateAgrochemicalAction;
use App\Actions\AgrochemicalManagement\UpdateAgrochemicalAction;

class AgrochemicalForm extends Form
{
    public TemporaryUploadedFile|string|null $thumbnail = null;
    public ?string $name = null, $type = null, $description = null;
    public ?float $quantity_per_unit = null, $price = null;
    public ?Agrochemical $agrochemical = null;

    public function rules(): array
    {
        return [
            'name'              => ['required', 'string', 'max:255','unique:agrochemicals,name,' . ($this->agrochemical?->id ?? 'NULL')],
            'quantity_per_unit' => ['required', 'numeric', 'min:0'],
            'price'             => ['required', 'numeric', 'min:0'],
            'type'              => ['required', 'string'],
            'description'       => ['nullable', 'string'],
            'thumbnail'         => ['nullable'],
        ];
    }

    public function edit(Agrochemical $agrochemical): void
    {
        $this->agrochemical = $agrochemical;
        $this->name = $agrochemical->name;
        $this->quantity_per_unit = $agrochemical->quantity_per_unit;
        $this->price = $agrochemical->price;
        $this->type = $agrochemical->type->value;
        $this->description = $agrochemical->description;
        $this->thumbnail = $agrochemical->thumbnail;
    }

    public function create(array $validatedData): void
    {
        app(CreateAgrochemicalAction::class)->handle(AgrochemicalDTO::fromArray($validatedData));
    }

    public function update($validatedData): void
    {
        app(UpdateAgrochemicalAction::class)->handle($this->agrochemical, AgrochemicalDTO::fromArray($validatedData));
    }
}
