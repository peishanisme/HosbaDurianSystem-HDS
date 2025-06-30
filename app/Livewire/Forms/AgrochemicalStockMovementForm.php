<?php

namespace App\Livewire\Forms;

use App\Actions\AgrochemicalManagement\CreateAgrochemicalStockAction;
use App\Actions\AgrochemicalManagement\UpdateAgrochemicalStockAction;
use App\DataTransferObject\AgrochemicalStockMovementDTO;
use App\Enum\StockMovementType;
use App\Models\Agrochemical;
use App\Models\AgrochemicalStockMovement;
use Livewire\Form;

class AgrochemicalStockMovementForm extends Form
{
    public ?Agrochemical $agrochemical = null;
    public ?AgrochemicalStockMovement $stock = null;
    public ?string $name, $agrochemical_uuid, $date, $description, $movement_type = StockMovementType::IN->value;
    public ?int $quantity = null;

    public function rules(): array
    {
        return [
            'agrochemical_uuid' => ['required', 'exists:agrochemicals,uuid'],
            'movement_type'     => ['required', 'string'],
            'quantity'          => ['required', 'integer', 'min:1'],
            'date'              => ['required', 'date', 'before_or_equal:today'],
            'description'       => ['nullable', 'string'],
        ];
    }

    public function edit(AgrochemicalStockMovement $stock): void
    {
        $this->stock = $stock;
        $this->agrochemical = $stock->agrochemical;
        $this->name = $stock->agrochemical->name;
        $this->agrochemical_uuid = $stock->agrochemical->uuid;
        $this->movement_type = $stock->movement_type;
        $this->quantity = $stock->quantity;
        $this->date = $stock->date?->format('Y-m-d');
        $this->description = $stock->description;
    }

    public function create(array $validatedData): void
    {
        app(CreateAgrochemicalStockAction::class)->handle(AgrochemicalStockMovementDTO::fromArray($validatedData));
    }

    public function update($validatedData): void
    {
        app(UpdateAgrochemicalStockAction::class)->handle($this->stock,AgrochemicalStockMovementDTO::fromArray($validatedData));
    }



    
}
