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

    protected $fillable = [
        'uuid',
        'tree_uuid',
        'harvest_uuid',
        'weight',
        'grade',
        'harvested_at',
        'transaction_uuid',
        'is_spoiled',
        'fruit_tag',
    ];

    protected static function booted()
    {
        static::creating(function ($fruit) {
            if (empty($fruit->uuid)) {
                $fruit->uuid = Str::uuid();
            }

            if (empty($fruit->fruit_tag)) {
                $latestFruit = Fruit::orderBy('id', 'desc')->first();

                if ($latestFruit && $latestFruit->fruit_tag) {
                    $lastNumber = (int) substr($latestFruit->fruit_tag, 2);
                    $newNumber = $lastNumber + 1;
                } else {
                    $newNumber = 1;
                }

                $fruit->fruit_tag = 'FR' . str_pad($newNumber, 6, '0', STR_PAD_LEFT);
            }
        });
    }

    /**
     * Relationships
     */

    public function tree()
    {
        return $this->belongsTo(Tree::class, 'tree_uuid', 'uuid');
    }

    public function harvestEvent()
    {
        return $this->belongsTo(HarvestEvent::class, 'harvest_uuid', 'uuid');
    }
}