<?php

namespace App\DataTransferObject;

use App\Traits\ToArrayTrait;

class UserDTO
{
    /**
     * Create a new class instance.
     */
    use ToArrayTrait;

    public function __construct(
        public string $name,
        public ?string $email = null,
        public string $phone,
        public string $role,
        public bool $is_active
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            name: $data['name'],
            email: $data['email'] ?? null,
            phone: $data['phone'],
            role: $data['role'],
            is_active: $data['is_active']
        );
    }
}
