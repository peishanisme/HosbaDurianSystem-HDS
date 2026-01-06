<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Support\Str;
use Spatie\Activitylog\LogOptions;
use App\Reports\Contracts\Reportable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Database\Eloquent\SoftDeletes;

class HarvestEvent extends Model implements Reportable
{
    use SoftDeletes, LogsActivity;

    protected $fillable = [
        'uuid',
        'event_name',
        'start_date',
        'end_date',
        'description',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logFillable()
            ->logOnlyDirty()
            ->useLogName('harvest_event')
            ->setDescriptionForEvent(function (string $eventName) {
                if ($eventName === 'updated' && $this->isDirty('end_date')) {
                    return "A harvest event has been closed.";
                }
                return "A harvest event has been $eventName.";
            })
            ->dontSubmitEmptyLogs();
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->uuid = (string) Str::uuid();
            $year = now()->year;
            $date = Carbon::parse($model->start_date)->format('Ymd');

            // Count how many harvest events already exist in this year
            $sequence = static::whereYear('created_at', $year)->count() + 1;

            $model->event_name = "HES{$sequence}-{$date}";
        });
    }

    public function fruits()
    {
        return $this->hasMany(Fruit::class, 'harvest_uuid', 'uuid');
    }

    public function trees()
    {
        return $this->hasManyThrough(
            Tree::class,
            Fruit::class,
            'harvest_uuid',
            'uuid',
            'uuid',
            'tree_uuid'
        )->distinct();
    }

    public static function reportQuery(array $filters): Builder
    {
        $reportType = $filters['reportType'] ?? 'record';

        return match ($reportType) {
            'tree'    => self::treeSummaryQuery($filters),
            'species' => self::speciesSummaryQuery($filters),
            default   => self::fruitRecordQuery($filters),
        };
    }

    protected static function fruitRecordQuery(array $filters): Builder
    {
        return \App\Models\Fruit::query()
            ->select('fruits.*')
            ->join('harvest_events', 'harvest_events.uuid', '=', 'fruits.harvest_uuid')
            ->with(['tree', 'tree.species', 'harvestEvent'])
            ->where('fruits.harvest_uuid', $filters['harvest_uuid'] ?? null)
            ->orderBy('harvested_at', 'asc')
            ->orderBy('fruit_tag', 'asc');;
    }

    protected static function treeSummaryQuery(array $filters): Builder
    {
        return \App\Models\Fruit::query()
            ->selectRaw('
            fruits.tree_uuid,
            trees.tree_tag,
            COUNT(*) as total_fruits,
            SUM(fruits.weight) as total_weight,
            AVG(fruits.weight) as avg_weight,
            SUM(CASE WHEN fruits.is_spoiled THEN 1 ELSE 0 END) as total_spoiled,
            SUM(CASE WHEN fruits.transaction_uuid IS NOT NULL THEN 1 ELSE 0 END) as total_sold
        ')
            ->join('trees', 'trees.uuid', '=', 'fruits.tree_uuid')
            ->where('fruits.harvest_uuid', $filters["harvest_uuid"] ?? null)
            ->groupBy('fruits.tree_uuid', 'trees.tree_tag')
            ->orderBy('trees.tree_tag');
    }

    protected static function speciesSummaryQuery(array $filters): Builder
    {
        return \App\Models\Fruit::query()
            ->join('trees', 'fruits.tree_uuid', '=', 'trees.uuid')
            ->join('species', 'trees.species_id', '=', 'species.id')
            ->where('fruits.harvest_uuid', $filters['harvest_uuid'] ?? null)
            ->selectRaw('
            species.id as species_id,
            species.name as species_name,
            COUNT(fruits.id) as total_fruits,
            AVG(fruits.weight) as avg_weight,
            SUM(fruits.weight) as total_weight
        ')
            ->groupBy('species.id', 'species.name');
    }

    public static function reportColumns(): array
    {
        return match (request('reportType')) {
            'tree' => self::treeColumns(),
            'species' => self::speciesColumns(),
            default => self::fruitRecordColumns(),
        };
    }

    protected static function fruitRecordColumns(): array
    {
        return [
            'Fruit Code'   => 'fruit_tag',
            'Tree'         => 'tree.tree_tag',
            'Species'      => 'tree.species.name',
            'Weight (kg)'  => 'weight',
            'Grade'        => 'grade',
            'Harvest Date' => 'harvested_at',
        ];
    }

    protected static function treeColumns(): array
    {
        return [
            'Tree Tag'        => 'tree_tag',
            'Total Fruits'   => 'total_fruits',
            'Total Weight (kg)' => 'total_weight',
            'Average Weight (kg)' => 'avg_weight',
            'Spoiled Fruits' => 'total_spoiled',
            'Sold Fruits'    => 'total_sold',
        ];
    }


    protected static function speciesColumns(): array
    {
        return [
            'Species'           => 'species_name',
            'Total Fruits'      => 'total_fruits',
            'Avg Weight (kg)'   => 'avg_weight',
            'Total Weight'  => 'total_weight',
        ];
    }

    public static function reportTitle(): string
    {
        return match (request('reportType')) {
            'tree'    => 'Harvest Event Report (By Tree)',
            'species' => 'Harvest Event Report (By Species)',
            default   => 'Harvest Event Report (Fruit Records)',
        };
    }
}
