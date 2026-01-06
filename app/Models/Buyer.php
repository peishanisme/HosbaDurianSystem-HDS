<?php

namespace App\Models;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Spatie\Activitylog\LogOptions;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Buyer extends Model
{
    use SoftDeletes, LogsActivity;

    protected $fillable = [
        'uuid',
        'company_name',
        'contact_name',
        'contact_number',
        'email',
        'address',
        'reference_id',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logFillable()
            ->logOnlyDirty()
            ->useLogName('buyer')
            ->setDescriptionForEvent(fn(string $eventName) => "A buyer has been $eventName.")
            ->dontSubmitEmptyLogs();
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->uuid = (string) Str::uuid();
            $model->reference_id = 'buyer-' . substr(Str::uuid()->toString(), 0, 25);
        });
    }

    public function transactions(): HasMany
    {
        return $this->hasMany(Transaction::class, 'buyer_uuid', 'uuid');
    }

    public function getTotalSpentAttribute(): float
    {
        return $this->transactions()
            ->where('is_cancelled', false)
            ->sum('total_price');
    }

    public function getTotalTransactionsAttribute(): int
    {
        return $this->transactions()
            ->where('is_cancelled', false)
            ->count();
    }

    public function getQuantityPurchasedAttribute(): int
    {
        return $this->transactions()
            ->where('is_cancelled', false)
            ->withCount(['fruits as total_quantity'])
            ->get()
            ->sum('total_quantity');
    }

    public function getTotalWeightPurchasedAttribute(): float
    {
        return (float) $this->transactions()
            ->where('is_cancelled', false)
            ->withCount([
                'fruits as total_weight' => function ($query) {
                    $query->select(DB::raw('COALESCE(SUM(weight), 0)'));
                }
            ])
            ->get()
            ->sum('total_weight');
    }
}
