<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Fruit extends Model
{
    use HasFactory;

    protected $table = 'fruits';
    protected $primaryKey = 'id';

    // Allow mass assignment for these fields
    protected $fillable = [
        'uuid',
        'tree_uuid',
        'harvest_uuid',
        'weight',
        'grade',
        'harvested_at',
    ];

    // Ensure UUID auto-generated when creating
    protected static function booted()
    {
        static::creating(function ($fruit) {
            if (empty($fruit->uuid)) {
                $fruit->uuid = Str::uuid();
            }
        });
    }

    /**
     * Relationships
     */

    // Fruit belongs to a Tree
    public function tree()
    {
        return $this->belongsTo(Tree::class, 'tree_uuid', 'uuid');
    }

    // Fruit belongs to a HarvestEvent
    public function harvestEvent()
    {
        return $this->belongsTo(HarvestEvent::class, 'harvest_uuid', 'uuid');
    }
}
