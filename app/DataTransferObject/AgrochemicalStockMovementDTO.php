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
        public string $type,
        public int $quantity,
        public string $date,
        public ?string $description = null,
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            agrochemical_uuid: $data['agrochemical_uuid'],
            type: $data['type'],
            quantity: $data['quantity'],
            date: $data['date'],
            description: $data['description'] ?? null,
        );
    }
}
