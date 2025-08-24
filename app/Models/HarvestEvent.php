<?php

namespace App\Models;

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
        });
    }
}
