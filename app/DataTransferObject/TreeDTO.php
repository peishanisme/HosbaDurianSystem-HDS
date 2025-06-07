<?php

namespace App\DataTransferObject;

use App\Traits\ToArrayTrait;

class TreeDTO
{
    /**
     * Create a new class instance.
     */
    use ToArrayTrait;

    public function __construct(
        public int $species_id,
        public string $planted_at,
        public ?string $thumbnail,
        public ?float $latitude,
        public ?float $longitude,
        public float $height,
        public float $diameter,
        public int $flowering_period,
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            species_id: $data['species_id'],
            planted_at: $data['planted_at'],
            thumbnail: $data['thumbnail'] ?? null,
            latitude: $data['latitude'] ?? null,
            longitude: $data['longitude'] ?? null,
            height: $data['height'] ,
            diameter: $data['diameter'],
            flowering_period: $data['flowering_period']
        );
    }
}
