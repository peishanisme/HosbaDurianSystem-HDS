<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Label extends Model
{
    protected $fillable = [
        'name',
        'label',
        'color',
    ];

    public function trees(): BelongsToMany
    {
        return $this->belongsToMany(Tree::class, 'tree_label', 'label_id', 'tree_id')
                    ->withTimestamps();
    }
}
