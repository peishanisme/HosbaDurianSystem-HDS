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
            ->setSearchPlaceholder(__('messages.search_purchase_history'))
            ->setEmptyMessage(__('messages.no_results_found'))
            ->setConfigurableAreas([
                'toolbar-right-end' => [
                    'livewire.components.modal-button',
                    [
                        'label' => __('messages.update_stock'),
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
            Column::make(__('messages.date'), "date")
                ->format(fn($value, $row, Column $column) => Carbon::parse($value)->format('Y-m-d'))
                ->sortable(),
            Column::make(__('messages.description'), "description")
                ->sortable()
                ->searchable(),
            Column::make(__('messages.quantity'), "quantity")
                ->sortable(),
            Column::make(__('messages.created_at'), "created_at")
                ->sortable(),
            Column::make(__('messages.actions'))
                ->label(fn($row, Column $column) => view('components.table-com-button', [
                    'modal'     => 'agrochemicalStockMovementModalLivewire',
                    'dispatch1' => 'edit-stock',
                    'label1'    => __('messages.edit'),
                    'dataField' => 'stock',
                    'data'      =>  $row->id,
                    'icon2'     => 'bi bi-trash3',
                    'dispatch2' => 'delete-stock',
                    'label2'    => __('messages.delete'),
                    'permission1' => 'update-stock-levels',
                    'permission2' => 'update-stock-levels',
                ]))->html()
                ->excludeFromColumnSelect(),
        ];
    }
}
