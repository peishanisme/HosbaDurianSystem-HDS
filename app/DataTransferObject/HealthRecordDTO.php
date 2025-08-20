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
        
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
           
        );
    }
}
