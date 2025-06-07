<?php

namespace App\DataTransferObject;

use App\Traits\ToArrayTrait;

class SpeciesDTO
{
    /**
     * Create a new class instance.
     */
    use ToArrayTrait;

    public function __construct(
        public string $name,
        public string $code,
        public ?string $description = null,
        public bool $is_active
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            name: $data['name'],
            code: $data['code'],
            description: $data['description'] ?? null,
            is_active: $data['is_active']
        );
    }
}
