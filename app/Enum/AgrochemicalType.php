<?php

namespace App\Enum;

enum AgrochemicalType: string
{
    case FERTILIZER = 'fertilizer';
    case PESTICIDE = 'pesticide';
    case OTHER = 'other';

    /**
     * Optionally: user-friendly labels
     */
    public function label(): string
    {
        return __('messages.agrochemical_type.' . $this->value);
    }

    public static function keyValue(): array
    {
        return collect(self::cases())
            ->mapWithKeys(fn(self $type) => [$type->value => $type->label()])
            ->toArray();
    }
}
