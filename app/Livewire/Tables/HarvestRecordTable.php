<?php

namespace App\Livewire\Tables;

use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;
use App\Models\HarvestEvent;
use App\Models\HarvestRecord;
use Illuminate\Database\Eloquent\Builder;
use Rappasoft\LaravelLivewireTables\Views\Columns\ViewComponentColumn;

class HarvestRecordTable extends DataTableComponent
{
    public ?HarvestEvent $harvestEvent = null;
    public function builder(): Builder
    {
        return HarvestRecord::query()
            ->where('harvest_uuid', $this->harvestEvent->uuid)
            ->with('tree');
    }

    public function configure(): void
    {
        $this->setPrimaryKey('id');
    }

    public function columns(): array
    {
        return [
            Column::make("Id", "id")
                ->sortable()
                ->hideIf(true),
            Column::make("Harvest uuid", "harvest_uuid")
                ->sortable()
                ->hideIf(true),
            Column::make("Tree uuid", "tree_uuid")
                ->sortable()
                ->hideIf(true),
            ViewComponentColumn::make(__('messages.tree_tag'), 'tree.tree_tag')
                ->component('components.table-primary-column')
                ->attributes(fn($value, $row, Column $column) => [
                    'title' => $value,
                    'route' => route('tree.show', $row->tree->id),
                ])->searchable()
                ->sortable(),
            Column::make("Harvest date", "harvest_date")
                ->format(fn($value) => $value->format('Y-m-d'))
                ->sortable(),
            Column::make("Num of fruits", "num_of_fruits")
                ->sortable(),
            Column::make("Weight", "weight")
                ->format(fn($value) => $value ? $value . ' kg' : '-')
                ->sortable(),
            ViewComponentColumn::make(__('messages.spoilt'), 'spoilt')
                ->component('table-badge')
                ->attributes(fn($value, $row, Column $column) => [
                    'badge' => $row->spoilt ?  'badge-light-danger' : 'badge-light-success',
                    'label' => $row->spoilt ? __('messages.spoilt') : __('messages.not_spoilt'),
                ]),
            Column::make("Created at", "created_at")
                ->sortable()
                ->hideIf(true),
            Column::make("Updated at", "updated_at")
                ->sortable()
                ->hideIf(true),
            // Column::make(__('messages.actions'))
            //     ->label(fn($row, Column $column) => view('components.table-button', [
            //         'icon' => 'bi-qr-code',
            //         'modal' => 'harvestQrCodeModalLivewire',
            //         'label' => __('messages.print_qr_code'),
            //         'dispatch' => 'load-qr-code',
            //         'dataField' => 'harvestRecord',
            //         'data' => $row->id,
            //     ]))->html()
            //     ->excludeFromColumnSelect(),
        ];
    }
}
