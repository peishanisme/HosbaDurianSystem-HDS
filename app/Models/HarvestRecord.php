<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class HarvestRecord extends Model
{
    use SoftDeletes;
    protected $fillable = [
        'harvest_uuid',
        'tree_uuid',
        'harvest_date',
        'num_of_fruits',
        'weight',
        'spoilt',
    ];

    protected $casts = [
        'harvest_date' => 'date:Y-m-d', 
        'spoilt' => 'boolean',
        'weight' => 'decimal:2',
    ];

    protected static function boot()
    {
        parent::boot();
        // Do not auto-generate `harvest_uuid` here. `harvest_uuid` is
        // a reference to `harvest_events.uuid` and should be provided
        // by callers or created by the HarvestEvent creation process.
    }

    public function tree(): BelongsTo
    {
        return $this->belongsTo(Tree::class, 'tree_uuid', 'uuid');
    }

    public function observations(): HasMany
    {
        return $this->hasMany(TreeObservation::class, 'harvest_uuid', 'harvest_uuid');
    }

}
