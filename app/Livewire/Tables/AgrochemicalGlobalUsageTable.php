<?php

namespace App\Livewire\Tables;

use App\Enum\AgrochemicalType;
use App\Models\AgrochemicalRecord;
use Illuminate\Database\Eloquent\Builder;
use Rappasoft\LaravelLivewireTables\Views\Column;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Filters\SelectFilter;
use Rappasoft\LaravelLivewireTables\Views\Columns\ViewComponentColumn;

class AgrochemicalGlobalUsageTable extends DataTableComponent
{
    public function builder(): Builder
    {
        return AgrochemicalRecord::query()
            ->with('agrochemical', 'tree');
    }

    public function configure(): void
    {
        $this->setPrimaryKey('id')
            ->setSearchPlaceholder(__('messages.search_agrochemical_usages'))
            ->setEmptyMessage(__('messages.no_results_found'))
            ->setDefaultSort('created_at', 'desc')
            ->setConfigurableAreas([
                'toolbar-right-end' => [
                    'livewire.components.modal-button',
                    [
                        'label' => __('messages.generate_report'),
                        'dispatch' => 'reset-generator',
                        'target' => 'generateReportModalLivewire',
                        'permission' => 'export-reports',
                    ]
                ]
            ]);
    }

    public function filters(): array
    {
        return [
            'type' => SelectFilter::make(__('messages.type'))
                ->options(['' => __('messages.any')] + AgrochemicalType::keyValue())
                ->filter(fn(Builder $query, $value) => $query->where('agrochemical.type', $value)),
        ];
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
             ViewComponentColumn::make(__('messages.agrochemical_name'), 'agrochemical.name')
                ->component('components.table-primary-column')
                ->attributes(fn($value, $row, Column $column) => [
                    'thumbnail' => $row->agrochemical->thumbnail ?? 'default',
                    'title' => $value,
                    'route' => route('agrochemical.show', $row->agrochemical->id),
                ])->searchable()
                ->sortable(),
            Column::make("Tree uuid", "tree_uuid")
                ->sortable()
                ->hideIf(true),
            ViewComponentColumn::make(__('messages.tree_tag'), 'tree.tree_tag')
                ->component('components.table-primary-column')
                ->attributes(fn($value, $row, Column $column) => [
                    'thumbnail' => $row->tree->thumbnail ?? 'default',
                    'title' => $value,
                    'route' => route('tree.show', $row->id),
                ])->searchable()
                ->sortable(),

            ViewComponentColumn::make(__('messages.type'), 'agrochemical.type')
                ->component('table-badge')
                ->attributes(fn($value, $row, Column $column) => [
                    'badge' => $value === 'pesticide' ?  'badge-light-info' : 'badge-light-primary',
                    'label' => ucfirst($value),
                ]),
            Column::make(__('messages.applied_at'), "applied_at")
                ->sortable(),
            Column::make(__('messages.description'), "description"),
            Column::make(__('messages.created_at'), "created_at")
                ->sortable(),
            Column::make(__('messages.updated_at'), "updated_at")
                ->sortable()
                ->hideIf(true),
        ];
    }
}
