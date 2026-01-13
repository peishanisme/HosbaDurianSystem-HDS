<?php

namespace App\Models;

use Spatie\Activitylog\LogOptions;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Str;

class HealthRecord extends Model
{
    use HasFactory, LogsActivity;
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = ['tree_uuid','disease_id', 'status', 'recorded_at', 'treatment', 'thumbnail'];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logFillable()
            ->logOnlyDirty()
            ->useLogName('health_record')
            ->setDescriptionForEvent(fn(string $eventName) => "A tree health record has been $eventName.")
            ->dontSubmitEmptyLogs();
    }

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            if (empty($model->{$model->getKeyName()})) {
                $model->{$model->getKeyName()} = (string) Str::uuid();
            }
        });
    }

    public function disease()
    {
        return $this->belongsTo(Disease::class);
    }

    public function tree()
    {
        return $this->belongsTo(Tree::class, 'tree_uuid', 'uuid'); 
    }
}
