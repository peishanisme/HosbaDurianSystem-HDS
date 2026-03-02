<?php

namespace App\DataTransferObject;

use App\Traits\ToArrayTrait;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;

class TreeDTO
{
    use ToArrayTrait;

    public function __construct(
        public int $species_id,
        public ?string $planted_at = null,
        public TemporaryUploadedFile|string|null $thumbnail,
        public ?float $latitude,
        public ?float $longitude,
        public ?float $height = null,
        public ?float $diameter = null,
        public ?int $flowering_period = null,
        public ?string $area = null,
        public ?int $terrace = null,
        public ?int $water_valve = null,
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            species_id: $data['species_id'],
            planted_at: $data['planted_at'] ?? null,
            thumbnail: $data['thumbnail'] ?? null, 
            latitude: $data['latitude'] ?? null,
            longitude: $data['longitude'] ?? null,
            height: $data['height'] ?? null,
            diameter: $data['diameter'] ?? null,
            flowering_period: $data['flowering_period'] ?? null,
            area: $data['area'] ?? null,
            terrace: $data['terrace'] ?? null,
            water_valve: $data['water_valve'] ?? null,
        );
    }

    public static function fromRequest($request): self
    {
        return self::fromArray($request->only([
            'species_id',
            'planted_at',
            'thumbnail',
            'flowering_period',
            'latitude',
            'longitude',
            'height',
            'diameter',
            'area',
            'terrace',
            'water_valve',
        ]));
    }
}
