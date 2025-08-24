<?php

namespace App\DataTransferObject;

use App\Traits\ToArrayTrait;

class DiseaseDTO
{
    /**
     * Create a new class instance.
     */
    use ToArrayTrait;

    public function __construct(
        public string $diseaseName,
        public ?string $symptoms = null,
        public ?string $remarks = null,
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            diseaseName: $data['diseaseName'],
            symptoms: $data['symptoms'] ?? null,
            remarks: $data['remarks'] ?? null,
        );
    }
}
