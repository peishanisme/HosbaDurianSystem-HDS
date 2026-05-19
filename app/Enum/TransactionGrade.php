<?php

namespace App\Enum;

enum TransactionGrade: string
{
    case A = 'A';
    case AB = 'AB';
    case B = 'B';
    case C = 'C';
    case CC = 'CC';
    case Z = 'Z';

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
