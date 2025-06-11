<?php

namespace App\Models;

use Spatie\Activitylog\LogOptions;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Species extends Model
{
    use LogsActivity, SoftDeletes;
    protected $fillable = [
        'name',
        'code',
        'description',
    ];

    /**
     * Get the species name.
     *
     * @return string
     */
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logFillable()
            ->logOnlyDirty()
            ->useLogName('species')
            ->setDescriptionForEvent(fn(string $eventName) => "A species has been $eventName.")
            ->dontSubmitEmptyLogs();
    }

    public function trees(): HasMany
    {
        return $this->hasMany(Tree::class);
    }


}
