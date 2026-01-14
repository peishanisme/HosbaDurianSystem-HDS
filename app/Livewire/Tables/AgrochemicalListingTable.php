<?php

namespace App\Livewire\Tables;

use App\Models\Agrochemical;
use App\Enum\AgrochemicalType;
use Illuminate\Database\Eloquent\Builder;
use Rappasoft\LaravelLivewireTables\Views\Column;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Filters\SelectFilter;
use Rappasoft\LaravelLivewireTables\Views\Columns\ViewComponentColumn;

class AgrochemicalListingTable extends DataTableComponent
{
    protected $model = Agrochemical::class;

    public function configure(): void
    {
        $this->setPrimaryKey('id')
            ->setSearchPlaceholder(__('messages.search_agrochemicals'))
            ->setEmptyMessage(__('messages.no_results_found'))
            ->setConfigurableAreas([
                'toolbar-right-end' => [
                    'livewire.components.modal-button',
                    [
                        'label' => __('messages.create_inventory'),
                        'dispatch' => 'reset-agrochemical',
                        'target' => 'agrochemicalModalLivewire',
                        'permission' => 'create-fertilizer-pesticide',
                    ]
                ]
            ]);
    }

    public function filters(): array
    {
        return [
            'type' => SelectFilter::make(__('messages.type'))
                ->options(['' => __('messages.any')] + AgrochemicalType::keyValue())
                ->filter(fn(Builder $query, $value) => $query->where('type', $value)),
        ];
    }


    public function columns(): array
    {
        return [
            Column::make("Id", "id")
                ->sortable()
                ->hideIf(true),
            Column::make("Thumbnail", "thumbnail")
                ->hideIf(true),
            ViewComponentColumn::make(__('messages.name'), 'name')
                ->component('components.table-primary-column')
                ->attributes(fn($value, $row, Column $column) => [
                    'thumbnail' => $row->thumbnail ?? 'default',
                    'title' => $value,
                    'route' => route('agrochemical.show', $row->id),
                ])->searchable()
                ->sortable(),
            Column::make(__('messages.quantity_per_unit'), "quantity_per_unit")
                ->format(fn($value) => number_format($value, 2)),
            ViewComponentColumn::make(__('messages.type'), 'type')
                ->component('table-badge')
                ->attributes(fn($value, $row, Column $column) => [
                    'badge' => $value === AgrochemicalType::PESTICIDE
                        ? 'badge-light-info'
                        : 'badge-light-primary',
                    'label' => $value->label(),
                ]),

            Column::make(__('messages.description'), "description"),
            Column::make(__('messages.actions'))
                ->label(fn($row, Column $column) => view('components.table-com-button', [
                    'modal'     => 'agrochemicalModalLivewire',
                    'dispatch1' => 'edit-agrochemical',
                    'label1'    => __('messages.edit'),
                    'dataField' => 'agrochemical',
                    'data'      =>  $row->id,
                    'icon2'     => 'bi bi-trash3',
                    'dispatch2' => 'delete-agrochemical',
                    'label2'    => __('messages.delete'),
                    'permission1' => 'edit-fertilizer-pesticide',
                    'permission2' => 'delete-fertilizer-pesticide',
                ]))->html()
                ->excludeFromColumnSelect(),

        ];
    }
}
