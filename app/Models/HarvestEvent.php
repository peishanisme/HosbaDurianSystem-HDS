<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Support\Str;
use Spatie\Activitylog\LogOptions;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Database\Eloquent\SoftDeletes;

class HarvestEvent extends Model
{
    use SoftDeletes, LogsActivity;

    protected $fillable = [
        'uuid',
        'event_name',
        'start_date',
        'end_date',
        'description',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logFillable()
            ->logOnlyDirty()
            ->useLogName('harvest_event')
            ->setDescriptionForEvent(function (string $eventName) {
                if ($eventName === 'updated' && $this->isDirty('end_date')) {
                    return "A harvest event has been closed.";
                }
                return "A harvest event has been $eventName.";
            })
            ->dontSubmitEmptyLogs();
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->uuid = (string) Str::uuid();
            $year = now()->year;
            $date = Carbon::parse($model->start_date)->format('Ymd');

            // Count how many harvest events already exist in this year
            $sequence = static::whereYear('created_at', $year)->count() + 1;

            $model->event_name = "HES{$sequence}-{$date}";
        });
    }

    public function fruits()
    {
        return $this->hasMany(Fruit::class, 'harvest_uuid', 'uuid');
    }

    public function trees()
    {
        return $this->hasManyThrough(
            Tree::class,
            Fruit::class,
            'harvest_uuid',  // Foreign key on Fruit table
            'uuid',            // Local key on Tree table (we’ll override this below)
            'uuid',          // Local key on HarvestEvent
            'tree_uuid'        // Foreign key on Fruit table to Tree
        )->distinct();
    }
}
