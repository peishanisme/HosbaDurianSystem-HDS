<?php

namespace App\Models;

use Spatie\Activitylog\LogOptions;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

class TreeGrowthLog extends Model
{
    use LogsActivity;
    
    protected $fillable = [
        'tree_id',
        'height',
        'diameter',
        'photo',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logFillable()
            ->logOnlyDirty()
            ->useLogName('tree growth')
            ->setDescriptionForEvent(fn(string $eventName) => "A tree growth log has been $eventName.")
            ->dontSubmitEmptyLogs();
    }

    public function tree()
    {
        return $this->belongsTo(Tree::class);
    }
}
