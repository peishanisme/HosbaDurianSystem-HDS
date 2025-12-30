<?php

namespace App\Livewire\Tables;

use App\Models\Agrochemical;
use Illuminate\Support\Carbon;
use App\Models\AgrochemicalStockMovement;
use Illuminate\Database\Eloquent\Builder;
use Rappasoft\LaravelLivewireTables\Views\Column;
use Rappasoft\LaravelLivewireTables\DataTableComponent;

class AgrochemicalPurchaseHistoryTable extends DataTableComponent
{
    public ?Agrochemical $agrochemical = null;
    
    protected $model = AgrochemicalStockMovement::class;

    public function builder(): Builder
    {
        return AgrochemicalStockMovement::where('agrochemical_uuid', $this->agrochemical?->uuid)
            ->with('agrochemical');
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
                        'label' => 'Update Stock',
                        'dispatch' => 'reset-stock',
                        'target' => 'agrochemicalStockMovementModalLivewire',
                        // 'permission' => 'create-user',
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
            Column::make("Agrochemical uuid", "agrochemical_uuid")
                ->sortable()
                ->hideIf(true),
            Column::make("Movement type", "movement_type")
                ->sortable()
                ->hideIf(true),
            Column::make("Date", "date")
            ->format(fn($value, $row, Column $column) => Carbon::parse($value)->format('Y-m-d'))
                ->sortable(),
            Column::make("Description", "description")
                ->sortable()
                ->searchable(),
            Column::make("Quantity", "quantity")
                ->sortable(),
            Column::make("Created at", "created_at")
                ->sortable(),
                Column::make('Actions')
                ->label(fn($row, Column $column) => view('components.table-com-button', [
                    'modal'     => 'agrochemicalStockMovementModalLivewire',
                    'dispatch1' => 'edit-stock',
                    'label1'    => 'Edit',
                    'dataField' => 'stock',
                    'data'      =>  $row->id,
                    'icon2'     => 'bi bi-trash3',
                    'dispatch2' => 'delete-stock',
                    'label2'    => 'Delete',
                    // 'permission1' => 'edit-agrochemical',
                    // 'permission2' => 'delete-agrochemical',
                ]))->html()
                ->excludeFromColumnSelect(),
        ];
    }
}
