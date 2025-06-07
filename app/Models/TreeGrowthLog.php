<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TreeGrowthLog extends Model
{
    protected $fillable = [
        'tree_id',
        'height',
        'diameter',
        'photo',
    ];

    public function tree()
    {
        return $this->belongsTo(Tree::class);
    }
}
