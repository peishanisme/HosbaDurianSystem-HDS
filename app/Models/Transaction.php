<?php

namespace App\Models;

use Illuminate\Support\Str;
use App\Enums\BlockchainStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Transaction extends Model
{
    use SoftDeletes;
    protected $fillable = [
        'uuid',
        'buyer_uuid',
        'date',
        'discount',
        'total_price',
        'blockchain_tx_hash',
        'blockchain_status',
        'synced_at',
        'reference_id',
        'payment_method',
        'remark',
        'price_per_kg',
    ];

    // protected $casts = [
    //     'blockchain_status' => BlockchainStatus::class,
    // ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->uuid = (string) Str::uuid();
            $model->reference_id = 'txn-' . substr(md5((string) Str::uuid()), 0, 27);
        });

    }

    public function buyer(): BelongsTo
    {
        return $this->belongsTo(Buyer::class, 'buyer_uuid', 'uuid');
    }

    
    /**
     * Get summary of fruits for this transaction, grouped by species and grade.
     *
     * @return array
     */
    public function getFruitSummary(): array
    {
        $fruits = $this->fruits()->with('tree.species')->get();

        $grouped = [];

        foreach ($fruits as $fruit) {
            $key = $fruit->tree->species->name . '-' . $fruit->grade;

            if (!isset($grouped[$key])) {
                $grouped[$key] = [
                    'species' => $fruit->tree->species->name,
                    'grade' => $fruit->grade,
                    'count' => 0,
                    'total_weight' => 0,
                    'price_per_kg' => $fruit->price_per_kg ?? 0,
                    'subtotal' => 0,
                ];
            }

            $grouped[$key]['count']++;
            $grouped[$key]['total_weight'] += $fruit->weight;
        }

        // calculate subtotals
        foreach ($grouped as &$item) {
            $item['subtotal'] = $item['total_weight'] * $item['price_per_kg'];
        }

        // sort by species then grade
        uasort($grouped, function ($a, $b) {
            $speciesCompare = strcmp($a['species'], $b['species']);
            return $speciesCompare === 0 ? strcmp($a['grade'], $b['grade']) : $speciesCompare;
        });

        return $grouped;
    }

    /**
     * Relation to fruits
     */
    public function fruits()
    {
        return $this->hasMany(Fruit::class, 'transaction_uuid', 'uuid');
    }

    /**
     * Calculate subtotal (sum of all fruit subtotals)
     */
    public function getSubtotalAttribute(): float
    {
        return collect($this->getFruitSummary())->sum('subtotal');
    }

    public function getSummaryAttribute(): array
    {
        //fruit summary
        //subtotal
        //total
        //discount
        //payment method
        return [
            'fruit_summary' => $this->getFruitSummary(),
            'subtotal' => $this->subtotal,
            'total' => $this->getSubtotalAttribute(), 
            'payment_method' => $this->payment_method,
        ];
    }
   
}
