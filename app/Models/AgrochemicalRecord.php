<?php

namespace App\Models;

use Illuminate\Support\Str;
use App\Reports\Contracts\Reportable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AgrochemicalRecord extends Model implements Reportable
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
    public function agrochemical(): BelongsTo
    {
        return $this->belongsTo(Agrochemical::class, 'agrochemical_uuid', 'uuid');
    }

    /**
     * Relationship: belongs to a Tree.
     */
    public function tree(): BelongsTo
    {
        return $this->belongsTo(Tree::class, 'tree_uuid', 'uuid');
    }

    public static function reportQuery(array $filters)
    {
        return self::with(['agrochemical', 'tree'])
            ->when($filters['from'] ?? null, fn($q, $from) =>
            $q->whereDate('applied_at', '>=', $from))
            ->when($filters['to'] ?? null, fn($q, $to) =>
            $q->whereDate('applied_at', '<=', $to));
    }

    //report 
    public static function reportColumns(): array
    {
        return [
            'Date'              => 'applied_at',
            'Agrochemical'      => 'agrochemical.name',
            // 'Type'              => 'agrochemical.type',
            'Tree'              => 'tree.tree_tag',
            'Description'       => 'description',
        ];
    }

    public static function reportTitle(): string
    {
        return 'Agrochemical Application Report';
    }
}
