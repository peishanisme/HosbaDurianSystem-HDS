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
            ->setSearchPlaceholder('Search Agrochemical')
            ->setEmptyMessage('No results found')
            ->setConfigurableAreas([
                'toolbar-right-end' => [
                    'livewire.components.modal-button',
                    [
                        'label' => 'Create Inventory',
                        'dispatch' => 'reset-agrochemical',
                        'target' => 'agrochemicalModalLivewire',
                        // 'permission' => 'create-user',
                    ]
                ]
            ]);
    }

    public function filters(): array
    {
        return [
            'type' => SelectFilter::make('Type')
                ->options(['' => 'Any'] + AgrochemicalType::keyValue())
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
            ViewComponentColumn::make('Name', 'name')
                ->component('components.table-primary-column')
                ->attributes(fn($value, $row, Column $column) => [
                    'thumbnail' => $row->thumbnail ?? 'default',
                    'title' => $value,
                    'route' => route('agrochemical.show', $row->id),
                ])->searchable()
                ->sortable(),
            Column::make("Quantity Per Unit", "quantity_per_unit")
                ->format(fn($value) => number_format($value, 2)),
            ViewComponentColumn::make('Type', 'type')
                ->component('table-badge')
                ->attributes(fn($value, $row, Column $column) => [
                    'badge' => 'badge-light-primary',
                    'label' => $value->label(),
                ]),
            Column::make("Description", "description"),
            Column::make('Actions')
                ->label(fn($row, Column $column) => view('components.table-com-button', [
                    'modal'     => 'agrochemicalModalLivewire',
                    'dispatch1' => 'edit-agrochemical',
                    'label1'    => 'Edit',
                    'dataField' => 'agrochemical',
                    'data'      =>  $row->id,
                    'icon2'     => 'bi bi-trash3',
                    'dispatch2' => 'delete-agrochemical',
                    'label2'    => 'Delete',
                    // 'permission1' => 'edit-agrochemical',
                    // 'permission2' => 'delete-agrochemical',
                ]))->html()
                ->excludeFromColumnSelect(),

        ];
    }
}
