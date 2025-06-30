<?php

namespace App\DataTransferObject;

use App\Traits\ToArrayTrait;

class AgrochemicalStockMovementDTO
{
    /**
     * Create a new class instance.
     */
    use ToArrayTrait;

    public function __construct(
        public string $agrochemical_uuid,
        public string $movement_type,
        public int $quantity,
        public string $date,
        public ?string $description = null,
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            agrochemical_uuid: $data['agrochemical_uuid'],
            movement_type: $data['movement_type'],
            quantity: $data['quantity'],
            date: $data['date'],
            description: $data['description'] ?? null,
        );
    }
}
