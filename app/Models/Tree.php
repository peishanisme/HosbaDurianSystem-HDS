<?php

namespace App\Models;

use Illuminate\Support\Str;
use Spatie\Activitylog\LogOptions;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Database\Eloquent\Relations\{HasMany, BelongsTo,HasOne};

class Tree extends Model
{
    use LogsActivity;
    protected $fillable = [
        'tree_tag',
        'species_id',
        'planted_at',
        'thumbnail',
        'latitude',
        'longitude',
        'flowering_period',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->tree_tag = static::generateTreeTag($model->species_id);
            $model->uuid = (string) Str::uuid();
        });

        static::updating(function ($model) {
            if ($model->isDirty('species_id')) {
                $model->tree_tag = static::generateTreeTag($model->species_id, $model->id);
            }
        });
    }

    public static function generateTreeTag($speciesId, $excludeId = null): string
    {
        $species = Species::findOrFail($speciesId);
        $speciesCode = $species->code;

        $query = static::where('species_id', $speciesId)
            ->where('tree_tag', 'like', "$speciesCode-%");

        if ($excludeId) {
            $query->where('id', '!=', $excludeId);
        }

        $latestTag = $query->orderByDesc('tree_tag')->value('tree_tag');

        $newNumber = $latestTag
            ? (int) substr($latestTag, strlen($speciesCode) + 1) + 1
            : 1;

        return $speciesCode . '-' . str_pad($newNumber, 3, '0', STR_PAD_LEFT);
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logFillable()
            ->logOnlyDirty()
            ->useLogName('tree')
            ->setDescriptionForEvent(fn(string $eventName) => "A tree has been $eventName.")
            ->dontSubmitEmptyLogs();
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

    public function firstGrowthLog(): HasOne
    {
        return $this->hasOne(TreeGrowthLog::class)->oldestOfMany();
    }

    public function latestGrowthLog(): HasOne
    {
        return $this->hasOne(TreeGrowthLog::class)->latestOfMany();
    }
}
