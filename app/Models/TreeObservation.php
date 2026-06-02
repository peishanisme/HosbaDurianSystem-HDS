<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TreeObservation extends Model
{
    protected $table = 'tree_observations';

    protected $fillable = [
        'tree_uuid',
        'harvest_uuid',
        'flowering_status',
    ];

    protected $casts = [
        'flowering_status' => 'string',
    ];

    public function tree(): BelongsTo
    {
        return $this->belongsTo(Tree::class, 'tree_uuid', 'uuid');
    }

    public function harvestEvent(): BelongsTo
    {
        return $this->belongsTo(HarvestEvent::class, 'harvest_uuid', 'uuid');
    }

}
