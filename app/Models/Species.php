<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Species extends Model
{
    protected $fillable = [
        'name',
        'code',
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

    public function trees(): HasMany
    {
        return $this->hasMany(Tree::class);
    }

    //get Tree Count
}
