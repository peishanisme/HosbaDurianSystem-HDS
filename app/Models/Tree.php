<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tree extends Model
{
    protected $fillable = [
        'species_id',
        'location',
        'height',
        'diameter',
        'health_status',
        'planted_at',
    ];

    public function species()
    {
        return $this->belongsTo(Species::class);
    }

    public function scopeActive($query)
    {
        return $query->whereHas('species', function ($q) {
            $q->where('is_active', true);
        });
    }
}
