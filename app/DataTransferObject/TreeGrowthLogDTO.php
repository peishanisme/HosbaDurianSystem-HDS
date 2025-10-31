<?php

namespace App\DataTransferObject;
use App\Traits\ToArrayTrait;

class TreeGrowthLogDTO
{
    use ToArrayTrait;
    
    public function __construct(
        // public int $tree_id,
        public string $tree_uuid,
        public float $height,
        public float $diameter,
        public ?string $photo = null,
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            // tree_id: $data['tree_id'],
            tree_uuid: $data['tree_uuid'],
            height: $data['height'],
            diameter: $data['diameter'],
            photo: $data['photo'] ?? null,
        );
    }
}
