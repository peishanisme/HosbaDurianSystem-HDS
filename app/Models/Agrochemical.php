<?php

namespace App\Models;

use Illuminate\Support\Str;
use App\Enum\AgrochemicalType;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Agrochemical extends Model
{
    use SoftDeletes;

    protected $casts = [
        'type' => AgrochemicalType::class,
    ];

    protected $fillable = [
        'uuid',
        'name',
        'quantity_per_unit',
        'price',
        'type',
        'description',
        'thumbnail',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->uuid = (string) Str::uuid();
        });
    }

}
