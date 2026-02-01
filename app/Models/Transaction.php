<?php

namespace App\Models;

use Illuminate\Support\Str;
use App\Enums\BlockchainStatus;
use Spatie\Activitylog\LogOptions;
use App\Reports\Contracts\Reportable;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Transaction extends Model implements Reportable
{
    use SoftDeletes, LogsActivity;
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
        'is_cancelled',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logFillable()
            ->logOnlyDirty()
            ->useLogName('transaction')
            ->setDescriptionForEvent(fn(string $eventName) => "A transaction has been $eventName.")
            ->dontSubmitEmptyLogs();
    }

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
            'buyer' => $this->buyer ? $this->buyer->company_name : 'Walk-in Customer',
            'buyer_address' => $this->buyer ? $this->buyer->address : '-',
            'buyer_reference_id' => $this->buyer ? $this->buyer->reference_id : '-',
            'buyer_phone' => $this->buyer ? $this->buyer->contact_number : '-',
            'buyer_email' => $this->buyer ? $this->buyer->email : '-',
            'date' => $this->date,
            'reference_id' => $this->reference_id,
            'remark' => $this->remark ?? '',
            'fruit_summary' => $this->getFruitSummary(),
            'subtotal' => $this->getSubtotalAttribute(),
            'total' =>  $this->total_price,
            'discount' => $this->discount,
            'payment_method' => $this->payment_method,
        ];
    }

    public static function reportQuery(array $filters)
    {
        return self::with(['buyer'])
            ->when(
                $filters['from'] ?? null,
                fn($q, $from) =>
                $q->whereDate('date', '>=', $from)
            )
            ->when(
                $filters['to'] ?? null,
                fn($q, $to) =>
                $q->whereDate('date', '<=', $to)
            )
            ->when(
                $filters['payment_method'] ?? null,
                fn($q, $method) =>
                $q->where('payment_method', $method)
            )
            ->when(
                $filters['buyer_uuid'] ?? null,
                fn($q, $buyer) =>
                $q->where('buyer_uuid', $buyer)
            );
    }

    //report 
    public static function reportColumns(): array
    {
        return [
            'Transaction Date'   => 'date',
            'Reference ID'       => 'reference_id',
            'Buyer'              => 'buyer.company_name',
            'Subtotal (RM)'      => 'subtotal',
            'Discount (RM)'      => 'discount',
            'Total Amount (RM)'  => 'total_price',
            'Payment Method'     => 'payment_method',
            'Remark'             => 'remark',
        ];
    }


    public static function reportTitle(): string
    {
        return 'Transaction Sales Report';
    }
}
