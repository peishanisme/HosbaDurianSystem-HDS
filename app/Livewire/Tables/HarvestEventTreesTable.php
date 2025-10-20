<?php

namespace App\Livewire\Tables;

use App\Models\Tree;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;

class HarvestEventTreesTable extends DataTableComponent
{
    public $harvestEvent;

    public bool $showDetails = true;
    /**
     * Query trees that have fruits in this event
     */
    public function builder(): Builder
    {
        return Tree::query()
            ->whereHas('fruits', function ($query) {
                $query->where('harvest_uuid', $this->harvestEvent->uuid);
            })
            ->with(['fruits' => function ($query) {
                $query->where('harvest_uuid', $this->harvestEvent->uuid);
            }]);
    }

    /**
     * Table columns
     */
    public function columns(): array
    {
        return [
            Column::make('Tree Code', 'tree_tag')
                ->sortable()
                ->searchable(),

            Column::make('Total Durians in Event')
                ->label(fn($row) => $row->fruits->count())
                ->html(),

            Column::make('Average Weight (kg)')
                ->label(function ($row) {
                    $avg = $row->fruits->avg('weight');
                    return $avg ? number_format($avg, 2) : '-';
                })
                ->html(),

            Column::make('Last Harvested')
                ->label(function ($row) {
                    $latest = $row->fruits->max('harvested_at');
                    return $latest ? \Carbon\Carbon::parse($latest)->format('Y-m-d H:i') : '-';
                }),
        ];
    }

    /**
     * Use a separate blade for fruit details
     */
    public function detailsView(): string
    {
        return 'livewire.components.harvest-tree-fruit-details';
    }

    public function configure(): void
    {
        $this->setPrimaryKey('id');
    }
}
