<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class HarvestGrade extends Model
{
    protected $table = 'harvest_grade';

    protected $fillable = [
        'harvest_uuid',
        'date',
        'grade',
        'weight',
        'species_id',
    ];

    protected $casts = [
        'date' => 'date:Y-m-d',
        'weight' => 'decimal:2',
    ];

    public function harvestEvent(): BelongsTo
    {
        return $this->belongsTo(HarvestEvent::class, 'harvest_uuid', 'uuid');
    }

    public function species(): BelongsTo
    {
        return $this->belongsTo(Species::class, 'species_id', 'id');
    }
}