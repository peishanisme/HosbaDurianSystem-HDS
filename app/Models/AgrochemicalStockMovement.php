<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AgrochemicalStockMovement extends Model
{
    protected $fillable = [
        'agrochemical_uuid',
        'movement_type',
        'date',
        'description',
        'quantity',
    ];

    public function agrochemical(): BelongsTo
    {
        return $this->belongsTo(Agrochemical::class, 'agrochemical_uuid', 'uuid');
    }
}
