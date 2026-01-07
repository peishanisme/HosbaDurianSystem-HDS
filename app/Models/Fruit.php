<?php

namespace App\Models;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

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
        'metadata_cid',
        'metadata_hash',
        'tx_hash',
        'onchain_at',
        'is_onchain',
        'metadata_version',
        'price_per_kg',
        'version',
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

    public function tree(): BelongsTo
    {
        return $this->belongsTo(Tree::class, 'tree_uuid', 'uuid');
    }

    public function harvestEvent(): BelongsTo
    {
        return $this->belongsTo(HarvestEvent::class, 'harvest_uuid', 'uuid');
    }

    public function feedbacks(): HasMany
    {
        return $this->hasMany(FruitFeedback::class, 'fruit_uuid', 'uuid');
    }

    public function getIsSoldAttribute(): bool
    {
        return !is_null($this->transaction_uuid);
    }
}