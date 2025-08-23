<?php

namespace App\Livewire\Tables;

use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;
use App\Models\HarvestEvent;

class HarvestEventListingTable extends DataTableComponent
{
    protected $model = HarvestEvent::class;

    public function configure(): void
    {
        $this->setPrimaryKey('id')
            ->setSearchPlaceholder('Search Harvest Event')
            ->setEmptyMessage('No results found')
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
                ->sortable(),
            Column::make("Uuid", "uuid")
                ->sortable(),
            Column::make("Event name", "event_name")
                ->sortable(),
            Column::make("Start date", "start_date")
                ->sortable(),
            Column::make("End date", "end_date")
                ->sortable(),
            Column::make("Description", "description")
                ->sortable(),
            Column::make("Created at", "created_at")
                ->sortable(),
            Column::make('Actions')
                ->label(fn($row, Column $column) => view('components.table-button', [
                    'modal'     => 'harvestEventModalLivewire',
                    'dispatch' => 'edit-harvest-event',
                    'label'    => 'Edit',
                    'dataField' => 'harvestEvent',
                    'data'      =>  $row->id
                ]))->html()
                ->excludeFromColumnSelect(),
        ];
    }
}
