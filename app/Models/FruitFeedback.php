<?php

namespace App\Models;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;

class FruitFeedback extends Model
{
    protected $fillable = [
        'uuid',
        'fruit_uuid',
        'feedback',
    ];

     protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->uuid = (string) Str::uuid();
        });
    }
}
