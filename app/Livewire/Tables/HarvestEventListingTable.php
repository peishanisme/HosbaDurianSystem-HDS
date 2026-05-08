<?php

namespace App\Livewire\Tables;

use App\Models\HarvestEvent;
use Illuminate\Database\Eloquent\Builder;
use Rappasoft\LaravelLivewireTables\Views\Column;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Columns\ViewComponentColumn;

class HarvestEventListingTable extends DataTableComponent
{
    public function builder(): Builder
    {
        return HarvestEvent::query()
            ->withSum(
                'harvestRecords as total_fruits',
                'num_of_fruits'
            );
    }
    public function configure(): void
    {
        $this->setPrimaryKey('id')
            ->setSearchPlaceholder(__('messages.search_harvest_events'))
            ->setEmptyMessage(__('messages.no_results_found'))
            ->setDefaultSort('start_date', 'desc')
            ->setConfigurableAreas([
                'toolbar-right-end' => [
                    'livewire.components.modal-button',
                    [
                        'label' =>  __('messages.add_harvest_event'),
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
            ViewComponentColumn::make(__('messages.event_name'), 'event_name')
                ->component('components.table-primary-column')
                ->attributes(fn($value, $row, Column $column) => [
                    'title' => $value,
                    'route' => route('harvest.show', $row->id),
                ])->searchable()
                ->sortable(),
            Column::make(__('messages.description'), "description")
                ->sortable(),
            Column::make(__('messages.start_date'), "start_date")
                ->format(fn($value) => $value ? \Carbon\Carbon::parse($value)->format('Y-m-d') : '-')
                ->sortable(),
            Column::make(__('messages.end_date'), "end_date")
                ->format(fn($value) => $value ? \Carbon\Carbon::parse($value)->format('Y-m-d') : '-')
                ->sortable(),
            Column::make(__('messages.total_fruits'))
                ->label(fn($row) => $row->total_fruits ?? 0)
                ->sortable(
                    fn(Builder $query, string $direction) =>
                    $query->orderBy('total_fruits', $direction)
                ),
            Column::make(__('messages.created_at'), "created_at")
                ->sortable(),
            Column::make(__('messages.actions'))
                ->label(fn($row, Column $column) => view('components.table-button', [
                    'modal'     => 'harvestEventModalLivewire',
                    'dispatch' => 'edit-harvest-event',
                    'label'    => __('messages.edit'),
                    'dataField' => 'harvestEvent',
                    'data'      =>  $row->id,
                    'permission' => 'edit-harvest-event',
                ]))->html()
                ->excludeFromColumnSelect(),
        ];
    }
}
