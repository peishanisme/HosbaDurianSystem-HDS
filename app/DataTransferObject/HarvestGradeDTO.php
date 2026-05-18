<?php

namespace App\DataTransferObject;

use App\Traits\ToArrayTrait;

class HarvestGradeDTO
{
    use ToArrayTrait;

    public function __construct(
        public ?int $id = null,
        public string $harvest_uuid,
        public string $date,
        public ?string $grade = null,
        public ?float $weight = null,
        public ?int $species_id = null,
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            id: $data['id'] ?? null,
            harvest_uuid: $data['harvest_uuid'],
            date: $data['date'],
            grade: $data['grade'] ?? null,
            weight: $data['weight'] ?? null,
            species_id: $data['species_id'] ?? null,
        );
    }
}