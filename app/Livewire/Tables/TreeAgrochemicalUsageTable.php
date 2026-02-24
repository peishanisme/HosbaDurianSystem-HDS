<?php

namespace App\Livewire\Tables;

use App\Models\Tree;
use App\Models\AgrochemicalRecord;
use Illuminate\Database\Eloquent\Builder;
use MessageFormatter;
use Rappasoft\LaravelLivewireTables\Views\Column;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Columns\ViewComponentColumn;

class TreeAgrochemicalUsageTable extends DataTableComponent
{
    public Tree $tree;

    public function builder(): Builder
    {
        return AgrochemicalRecord::query()
            ->where('tree_uuid', $this->tree->uuid)
            ->with('agrochemical');
    }

    public function configure(): void
    {
        $this->setPrimaryKey('id')
        ->setEmptyMessage(__('messages.no_results_found'))
        ->setSearchPlaceholder(__('messages.search_agrochemical_usages'))
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
            ViewComponentColumn::make(__('messages.agrochemical'), 'agrochemical.name')
                ->component('components.table-primary-column')
                ->attributes(fn($value, $row, Column $column) => [
                    'thumbnail' => $row->agrochemical->thumbnail ?? 'default',
                    'title' => $value,
                    'route' => route('agrochemical.show', $row->agrochemical->id),
                ])->searchable(),
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
