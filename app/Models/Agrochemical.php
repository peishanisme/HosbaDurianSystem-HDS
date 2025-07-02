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

    public function stockMovements()
    {
        return $this->hasMany(AgrochemicalStockMovement::class, 'agrochemical_uuid', 'uuid');
    }

    public function getRemainingStock(): int
{
    return $this->stockMovements()
        ->selectRaw("
            SUM(CASE WHEN movement_type = 'IN' THEN quantity ELSE 0 END) -
            SUM(CASE WHEN movement_type = 'OUT' THEN quantity ELSE 0 END) AS stock_remaining
        ")
        ->value('stock_remaining') ?? 0;
}


}
