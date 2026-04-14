<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TreeLabel extends Model
{
    protected $table = 'tree_label';

    protected $fillable = [
        'tree_id',
        'label_id',
    ];

    public function tree()
    {
        return $this->belongsTo(Tree::class);
    }

    public function label()
    {
        return $this->belongsTo(Label::class);
    }
}
