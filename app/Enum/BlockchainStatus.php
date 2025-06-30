<?php

namespace App\Enums;

enum BlockchainStatus: string
{
    case PENDING = 'pending';
    case CONFIRMED = 'confirmed';
    case FAILED = 'failed';
}
