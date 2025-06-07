<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Tree extends Model
{
    protected $fillable = [
        'tree_tag',
        'species_id',
        'planted_at',
        'thumbnail',
        'latitude',
        'longitude',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            // Count existing trees for the same species
            $count = static::where('species_id', $model->species_id)->count() + 1;
            $model->tree_tag = $model->species->code . '-' . str_pad($count, 3, '0', STR_PAD_LEFT);
        });
    }

    public function scopeActive($query)
    {
        return $query->whereHas('species', function ($q) {
            $q->where('is_active', true);
        });
    }

    public function species(): BelongsTo
    {
        return $this->belongsTo(Species::class);
    }

    public function growthLogs(): HasMany
    {
        return $this->hasMany(TreeGrowthLog::class);
    }

    public function latestGrowthLog()
    {
        return $this->hasOne(TreeGrowthLog::class)->latestOfMany();
    }
}
