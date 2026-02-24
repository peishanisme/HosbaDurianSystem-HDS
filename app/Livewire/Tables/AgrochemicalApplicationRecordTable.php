<?php

namespace App\Livewire\Tables;

use App\Models\Agrochemical;
use App\Models\AgrochemicalRecord;
use Illuminate\Database\Eloquent\Builder;
use Rappasoft\LaravelLivewireTables\Views\Column;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Columns\ViewComponentColumn;

class AgrochemicalApplicationRecordTable extends DataTableComponent
{
    public ?Agrochemical $agrochemical = null;

    public function builder(): Builder
    {
        return AgrochemicalRecord::where('agrochemical_uuid', $this->agrochemical?->uuid)
            ->with('agrochemical', 'tree');
    }

    public function configure(): void
    {
        $this->setPrimaryKey('id')
            ->setSearchPlaceholder(__('messages.search_agrochemical_applications'))
            ->setEmptyMessage(__('messages.no_results_found'))
            ->setDefaultSort('applied_at', 'desc');
    }

    public function columns(): array
    {
        return [
            Column::make("Id", "id")
                ->sortable()
                ->hideIf(true),
            Column::make("Agrochemical uuid", "agrochemical_uuid")
                ->sortable()
                ->hideIf(true),
            Column::make("Tree uuid", "tree_uuid")
                ->sortable()
                ->hideIf(true),
            ViewComponentColumn::make(__('messages.tree_tag'), 'tree.tree_tag')
                ->component('components.table-primary-column')
                ->attributes(fn($value, $row, Column $column) => [
                    'thumbnail' => $row->tree->thumbnail ?? 'default',
                    'title' => $value,
                    'route' => route('tree.show', $row->tree->id),
                ])->searchable()
                ->sortable(),
            Column::make(__('messages.species'), "tree.species.name")
                ->sortable(),
            Column::make(__('messages.applied_at'), "applied_at")
                ->sortable(),
            Column::make(__('messages.description'), "description"),
            Column::make(__('messages.created_at'), "created_at")
                ->sortable(),
            // Column::make("Updated at", "updated_at")
            //     ->sortable(),
        ];
    }
}
