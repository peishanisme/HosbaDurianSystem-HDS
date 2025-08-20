<?php

namespace App\Models;

use Spatie\Activitylog\LogOptions;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Disease extends Model
{
    use LogsActivity;
    protected $fillable = [
        'diseaseName',
        'symptoms',
        'remarks',
    ];

    /**
     * Get the disease name.
     *
     * @return string
     */
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logFillable()
            ->logOnlyDirty()
            ->useLogName('disease')
            ->setDescriptionForEvent(fn(string $eventName) => "A disease has been $eventName.")
            ->dontSubmitEmptyLogs();
    }

    public function diseases(): HasMany
    {
        return $this->hasMany(Disease::class);
    }

    public function tree()
    {
        return $this->belongsTo(Tree::class, 'tree_uuid', 'uuid');
    }

    public function healthRecords()
    {
        return $this->hasMany(HealthRecord::class);
    }


}
