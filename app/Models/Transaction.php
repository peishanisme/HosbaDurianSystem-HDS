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
        'total_price',
        'blockchain_tx_hash',
        'blockchain_status',
        'synced_at',
        'reference_id',
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

   
}
