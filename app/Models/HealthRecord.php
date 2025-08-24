<?php

namespace App\Models;

use Spatie\Activitylog\LogOptions;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class HealthRecord extends Model
{
    protected $fillable = ['tree_uuid','disease_id', 'status', 'recorded_at', 'treatment'];

    public function disease()
    {
        return $this->belongsTo(Disease::class);
    }

    public function tree()
    {
        return $this->belongsTo(Tree::class, 'tree_uuid', 'uuid'); 
    }
}
