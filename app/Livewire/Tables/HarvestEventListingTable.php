<?php

namespace App\Livewire\Tables;

use App\Models\HarvestEvent;
use Rappasoft\LaravelLivewireTables\Views\Column;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Columns\ViewComponentColumn;

class HarvestEventListingTable extends DataTableComponent
{
    protected $model = HarvestEvent::class;

    public function configure(): void
    {
        $this->setPrimaryKey('id')
            ->setSearchPlaceholder('Search Harvest Event')
            ->setEmptyMessage('No results found')
            ->setDefaultSort('start_date', 'desc')
            ->setConfigurableAreas([
                'toolbar-right-end' => [
                    'livewire.components.modal-button',
                    [
                        'label' => 'Create Harvest Event',
                        'dispatch' => 'reset-harvest-event',
                        'target' => 'harvestEventModalLivewire',
                        'permission' => 'create-harvest-event',
                    ]
                ]
            ]);
    }

    public function columns(): array
    {
        return [
            Column::make("Id", "id")
                ->sortable()
                ->hideIf(true),
            Column::make("Uuid", "uuid")
                ->sortable()
                ->hideIf(true),
            ViewComponentColumn::make('Event name', 'event_name')
                ->component('components.table-primary-column')
                ->attributes(fn($value, $row, Column $column) => [
                    'title' => $value,
                    'route' => route('harvest.show', $row->id),
                ])->searchable()
                ->sortable(),
            Column::make("Description", "description")
                ->sortable(),
            Column::make("Start date", "start_date")
                ->sortable(),
            Column::make("End date", "end_date")
                ->sortable(),
            Column::make("Created at", "created_at")
                ->sortable(),
            Column::make('Actions')
                ->label(fn($row, Column $column) => view('components.table-button', [
                    'modal'     => 'harvestEventModalLivewire',
                    'dispatch' => 'edit-harvest-event',
                    'label'    => 'Edit',
                    'dataField' => 'harvestEvent',
                    'data'      =>  $row->id,
                    'permission' => 'edit-harvest-event',
                ]))->html()
                ->excludeFromColumnSelect(),
        ];
    }
}
