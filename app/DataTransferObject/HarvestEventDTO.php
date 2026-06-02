<?php

namespace App\DataTransferObject;

use App\Traits\ToArrayTrait;

class HarvestEventDTO
{
    /**
     * Create a new class instance.
     */
    use ToArrayTrait;

    public function __construct(
        public string $event_name,
        public string $start_date,
        public ?string $end_date = null,
        public ?string $description = null,
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            event_name: $data['event_name'],
            start_date: $data['start_date'],
            end_date: $data['end_date'] ?? null,
            description: $data['description'] ?? null,
        );
    }
}
