<?php

namespace App\DataTransferObject;

use App\Traits\ToArrayTrait;

class AgrochemicalRecordDTO
{
    use ToArrayTrait;

    public function __construct(
        public ?string $agrochemical_uuid,
        public string $description,
        public ?string $applied_at = null,
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            agrochemical_uuid: $data['agrochemical_uuid'] ?? null,
            description: $data['description'] ?? '',
            applied_at: $data['applied_at'] ?? null,
        );
    }
}
