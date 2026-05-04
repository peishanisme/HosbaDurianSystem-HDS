<?php

namespace App\Models;

use App\Models\Label;
use App\Models\TreeLabel;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\{HasMany, BelongsTo, HasOne};
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Tree extends Model
{
    use LogsActivity, SoftDeletes;

    protected $fillable = [
        'tree_tag',
        'species_id',
        'planted_at',
        'thumbnail',
        'latitude',
        'longitude',
        'flowering_period',
        'area',
        'terrace',
        'water_valve',
    ];

    protected $casts = [
        'planted_at' => 'date',
        'terrace' => 'integer',
        'water_valve' => 'integer',
        'flowering_period' => 'integer',
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

                // Get current sequence number
                if (preg_match('/-(\d+)$/', $model->tree_tag, $matches)) {
                    $sequence = $matches[1];
                } else {
                    $sequence = '0000';
                }

                // Get new species code
                $species = Species::findOrFail($model->species_id);
                $speciesCode = $species->code;

                // Build new tag with same sequence
                $model->tree_tag = $speciesCode . '-' . $sequence;
            }
        });
    }

    public static function generateTreeTag($speciesId, $excludeId = null): string
    {
        $species = Species::findOrFail($speciesId);
        $speciesCode = $species->code;

        $latestTag = static::when($excludeId, fn($q) => $q->where('id', '!=', $excludeId))
            ->withTrashed()
            ->orderByDesc('id')
            ->value('tree_tag');

        if ($latestTag && preg_match('/-(\d+)$/', $latestTag, $matches)) {
            $latestNumber = (int) $matches[1];
            $newNumber = $latestNumber + 1;
        } else {
            $newNumber = 1;
        }

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
        return $this->belongsTo(Species::class, 'species_id', 'id');
    }

    public function growthLogs(): HasMany
    {
        return $this->hasMany(TreeGrowthLog::class, 'tree_uuid', 'uuid');
    }

    public function firstGrowthLog(): HasOne
    {
        return $this->hasOne(TreeGrowthLog::class, 'tree_uuid', 'uuid')->oldestOfMany();
    }

    public function latestGrowthLog(): HasOne
    {
        return $this->hasOne(TreeGrowthLog::class, 'tree_uuid', 'uuid')->latestOfMany();
    }

    public function diseases(): BelongsToMany
    {
        return $this->belongsToMany(Disease::class, 'health_records', 'tree_uuid', 'disease_id', 'uuid', 'id')
            ->withPivot('status', 'recorded_at', 'treatment')
            ->withTimestamps();
    }

    public function labels(): BelongsToMany
    {
        return $this->belongsToMany(Label::class, 'tree_label', 'tree_id', 'label_id')
            ->withTimestamps();
    }

    public function latestLabel(): HasOne
    {
        return $this->hasOne(TreeLabel::class, 'tree_id', 'id')->latestOfMany();
    }

    public function healthRecords(): HasMany
    {
        return $this->hasMany(HealthRecord::class, 'tree_uuid', 'uuid');
    }

    public function fruits(): HasMany
    {
        return $this->hasMany(Fruit::class, 'tree_uuid', 'uuid');
    }

    public function harvestRecords(): HasMany
    {
        return $this->hasMany(HarvestRecord::class, 'tree_uuid', 'uuid');
    }

    public function observations(): HasMany
    {
        return $this->hasMany(TreeObservation::class, 'tree_uuid', 'uuid');
    }

    public function activeObservation(): HasOne
    {
        return $this->hasOne(TreeObservation::class, 'tree_uuid', 'uuid')
            ->whereHas('harvestEvent', fn($q) => $q->active());
    }

    public function feedbacks(): HasMany
    {
        return $this->hasMany(FruitFeedback::class, 'tree_uuid', 'uuid');
    }   

    public function getActiveFloweringStatusAttribute()
    {
        return $this->activeObservation?->flowering_status;
    }

    public function fruitCountInHarvest($harvestUuid)
    {
        return $this->fruits()
            ->where('harvest_uuid', $harvestUuid)
            ->count();
    }

    public function getFloweringPeriod(): int
    {
        $harvestCount = $this->fruits()
            ->whereNotNull('harvest_uuid')
            ->distinct('harvest_uuid')
            ->count('harvest_uuid');

        return $this->flowering_period + $harvestCount;
    }
}
