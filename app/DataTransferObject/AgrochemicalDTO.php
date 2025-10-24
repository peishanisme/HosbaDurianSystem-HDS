<?php

namespace App\DataTransferObject;

use App\Traits\ToArrayTrait;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;

class AgrochemicalDTO
{
    /**
     * Create a new class instance.
     */
    use ToArrayTrait;

    public function __construct(
        public string $name,
        public float $quantity_per_unit,
        public float $price,
        public string $type,
        public ?string $description = null,
        public TemporaryUploadedFile|string|null $thumbnail = null,
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            name: $data['name'],
            quantity_per_unit: $data['quantity_per_unit'],
            price: $data['price'],
            type: $data['type'],
            description: $data['description'] ?? null,
            thumbnail: $data['thumbnail'] ?? null,
        );
    }
}
