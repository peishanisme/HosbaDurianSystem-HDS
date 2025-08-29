<?php

namespace App\DataTransferObject;

use App\Traits\ToArrayTrait;

class FruitDTO
{
    use ToArrayTrait;

    public function __construct(
        public ? string $harvest_uuid,
        public ? string $transaction_uuid,
        public ? string $harvested_at,
        public bool $is_spoiled = false,
        public ? string $tree_uuid,
        public ? float $weight,
        public ? string $grade
    ){}

    public static function fromArray(array $data): self
    {
        return new self(
            harvest_uuid: $data['harvest_uuid'] ?? null,
            transaction_uuid: $data['transaction_uuid'] ?? null,
            harvested_at: $data['harvested_at'] ?? null,
            is_spoiled: $data['is_spoiled'] ?? false,
            tree_uuid: $data['tree_uuid'] ?? null,
            weight: $data['weight'] ?? null,
            grade: $data['grade'] ?? null
        );
    }
}
