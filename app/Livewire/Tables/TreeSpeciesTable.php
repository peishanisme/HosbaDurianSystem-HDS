<?php

namespace App\Livewire\Tables;

use App\Models\Species;
use Rappasoft\LaravelLivewireTables\Views\Column;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Columns\ViewComponentColumn;

class TreeSpeciesTable extends DataTableComponent
{
    protected $model = Species::class;

    public function configure(): void
    {
        $this->setPrimaryKey('id')
            ->setSearchPlaceholder('Search Species')
            ->setEmptyMessage('No results found')
            ->setConfigurableAreas([
                'toolbar-right-end' => [
                    'livewire.components.modal-button',
                    [
                        'label' => 'Create Species',
                        'dispatch' => 'reset-species',
                        'target' => 'speciesModalLivewire'
                    ]
                ]
            ]);
    }

    public function columns(): array
    {
        return [
            Column::make("ID", "id")
                ->hideIf(true),
            Column::make("Name", "name")
                ->sortable(),
            Column::make("Description", "description")
                ->sortable(),
            ViewComponentColumn::make('Status', 'is_active')
                ->component('table-badge')
                ->attributes(fn($value, $row, Column $column) => [
                    'badge' => $row->is_active ?  'badge-light-success' : 'badge-light-danger',
                    'label' => $row->is_active ? 'Active' : 'Suspended',
                ]),
            Column::make("Created at", "created_at")
                ->sortable(),
            Column::make('Actions')
                ->label(fn($row, Column $column) => view('components.table-button', [
                    'modal' => 'speciesModalLivewire',
                    'dispatch' => 'edit-species',
                    'dataField' => 'species',
                    'data' => $row->id,
                    'permission' => 'edit-species'
                ]))->html()
                ->excludeFromColumnSelect(),
        ];
    }
}
