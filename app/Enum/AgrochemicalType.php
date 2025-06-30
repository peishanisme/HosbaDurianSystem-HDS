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
        return match ($this) {
            self::FERTILIZER => 'Fertilizer',
            self::PESTICIDE => 'Pesticide',
            self::OTHER => 'Other',
        };
    }

    public static function keyValue(): array
    {
        return collect(self::cases())
            ->mapWithKeys(fn(self $type) => [$type->value => $type->label()])
            ->toArray();
    }
}
