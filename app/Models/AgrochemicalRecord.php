<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;

class AgrochemicalRecord extends Model
{
    use HasFactory;

    protected $table = 'tree_agrochemicals';

    protected $fillable = [
        'agrochemical_uuid',
        'tree_uuid',
        'applied_at',
        'description',
    ];

    /**
     * Automatically generate UUID on create.
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->uuid)) {
                $model->uuid = (string) Str::uuid();
            }
        });
    }

    /**
     * Relationship: belongs to an Agrochemical.
     */
    public function agrochemical()
    {
        return $this->belongsTo(Agrochemical::class, 'agrochemical_uuid', 'uuid');
    }

    /**
     * Relationship: belongs to a Tree.
     */
    public function tree()
    {
        return $this->belongsTo(Tree::class, 'tree_uuid', 'uuid');
    }
}
