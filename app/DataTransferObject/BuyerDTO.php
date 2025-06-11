<?php

namespace App\DataTransferObject;

use App\Traits\ToArrayTrait;

class BuyerDTO
{
    /**
     * Create a new class instance.
     */
    use ToArrayTrait;

    public function __construct(
        public string $company_name,
        public ?string $contact_name = null,
        public string $contact_number,
        public ?string $email = null,
        public ?string $address = null,
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            company_name: $data['company_name'],
            contact_name: $data['contact_name'] ?? null,
            contact_number: $data['contact_number'],
            email: $data['email'] ?? null,
            address: $data['address'] ?? null,
        );
    }
}
