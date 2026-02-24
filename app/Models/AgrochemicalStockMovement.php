<?php

namespace App\Models;

use Illuminate\Support\Str;
use Spatie\Activitylog\LogOptions;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AgrochemicalStockMovement extends Model
{
    use SoftDeletes, LogsActivity;
    
    protected $fillable = [
        'agrochemical_uuid',
        'movement_type',
        'date',
        'description',
        'quantity',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logFillable()
            ->logOnlyDirty()
            ->useLogName('agrochemical_stock_movement')
            ->setDescriptionForEvent(fn(string $eventName) => "An agrochemical stock movement has been $eventName.")
            ->dontSubmitEmptyLogs();
    }

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
