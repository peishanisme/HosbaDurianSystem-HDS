<?php

namespace App\DataTransferObject;

use App\Traits\ToArrayTrait;

class AgrochemicalRecordDTO
{
    use ToArrayTrait;

    public function __construct(
        public ?string $tree_uuid,
        public ?string $agrochemical_uuid,
        public ?string $description = null,
        public ?string $applied_at = null,
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            tree_uuid: $data['tree_uuid'] ?? null,
            agrochemical_uuid: $data['agrochemical_uuid'] ?? null,
            description: $data['description'] ?? null,
            applied_at: $data['applied_at'] ?? null,
        );
    }
}
