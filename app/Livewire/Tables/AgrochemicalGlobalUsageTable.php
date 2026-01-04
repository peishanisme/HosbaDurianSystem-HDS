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
            ->setSearchPlaceholder('Search Stock')
            ->setEmptyMessage('No results found')
            ->setConfigurableAreas([
                'toolbar-right-end' => [
                    'livewire.components.modal-button',
                    [
                        'label' => 'Generate Report',
                        'dispatch' => 'reset-generator',
                        'target' => 'generateReportModalLivewire',
                        // 'permission' => 'create-buyer',
                    ]
                ]
            ]);
    }

    public function filters(): array
    {
        return [
            'type' => SelectFilter::make('Type')
                ->options(['' => 'Any'] + AgrochemicalType::keyValue())
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
            Column::make("Agrochemical", "agrochemical.name")
                ->sortable()
                ->searchable(),
            Column::make("Tree uuid", "tree_uuid")
                ->sortable()
                ->hideIf(true),
            Column::make("Tree", "tree.tree_tag")
                ->sortable()
                ->searchable(),
            ViewComponentColumn::make('Type', 'agrochemical.type')
                ->component('table-badge')
                ->attributes(fn($value, $row, Column $column) => [
                    'badge' => $value === 'pesticide' ?  'badge-light-info' : 'badge-light-primary',
                    'label' => ucfirst($value),
                ]),
            Column::make("Applied at", "applied_at")
                ->sortable(),
            Column::make("Description", "description"),
            Column::make("Created at", "created_at")
                ->sortable()
                ->hideIf(true),
            Column::make("Updated at", "updated_at")
                ->sortable()
                ->hideIf(true),
        ];
    }
}
