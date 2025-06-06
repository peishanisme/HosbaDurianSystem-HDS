<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Species extends Model
{
    protected $fillable = [
        'name',
        'description',
        'is_active',
    ];

    /**
     * Get the species name.
     *
     * @return string
     */
    
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    //get Tree Count
}
