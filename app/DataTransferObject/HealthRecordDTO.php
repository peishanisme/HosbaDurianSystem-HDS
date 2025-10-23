<?php

namespace App\DataTransferObject;

use App\Traits\ToArrayTrait;

class HealthRecordDTO
{
    use ToArrayTrait;

    public function __construct(
        public ?string $id = null,
        public int $disease_id,
        public string $status,
        public ?string $recorded_at = null,
        public ?string $treatment = null,
        public TemporaryUploadedFile|string|null $thumbnail,

    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            id: $data['id'] ?? null,
            disease_id: $data['disease_id'],
            status: $data['status'],
            recorded_at: $data['recorded_at'] ?? null,
            treatment: $data['treatment'] ?? null,
            thumbnail: $data['thumbnail'] ?? null, 
        );
    }
}
