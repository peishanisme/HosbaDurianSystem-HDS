<?php

namespace App\Models;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;

class FruitFeedback extends Model
{
    protected $fillable = [
        'uuid',
        'tree_uuid',
        'feedback',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->uuid = (string) Str::uuid();
        });
    }

    public function tree()
    {
        return $this->belongsTo(Tree::class, 'tree_uuid', 'uuid');
    }
}
