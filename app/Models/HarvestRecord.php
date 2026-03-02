<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class HarvestRecord extends Model
{
    protected $fillable = [
        'harvest_uuid',
        'tree_uuid',
        'harvest_date',
        'num_of_fruits',
        'weight',
        'spoilt',
    ];

    protected $casts = [
        'harvest_date' => 'date',
        'spoilt' => 'boolean',
        'weight' => 'decimal:2',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->harvest_uuid)) {
                $model->harvest_uuid = (string) Str::uuid();
            }
        });
    }

    public function tree(): BelongsTo
    {
        return $this->belongsTo(Tree::class, 'tree_uuid', 'uuid');
    }

    public function observations(): HasMany
    {
        return $this->hasMany(TreeObservation::class, 'harvest_uuid', 'harvest_uuid');
    }
}
