<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AgrochemicalStockMovement extends Model
{
    use SoftDeletes;
    
    protected $fillable = [
        'agrochemical_uuid',
        'movement_type',
        'date',
        'description',
        'quantity',
    ];

     protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->uuid = (string) Str::uuid();
        });
    }

    public function agrochemical(): BelongsTo
    {
        return $this->belongsTo(Agrochemical::class, 'agrochemical_uuid', 'uuid');
    }
}
