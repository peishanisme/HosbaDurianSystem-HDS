<?php

namespace App\Models;

use Spatie\Activitylog\LogOptions;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class HealthRecord extends Model
{
    protected $fillable = ['disease_id', 'status', 'recorded_at', 'treatment'];

    public function disease()
    {
        return $this->belongsTo(Disease::class);
    }
}
