<?php

namespace App\Livewire\Tables;

use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;
use App\Models\AgrochemicalStockMovement;

class PurchaseHistoryTable extends DataTableComponent
{
    protected $model = AgrochemicalStockMovement::class;

    public function configure(): void
    {
        $this->setPrimaryKey('id');
    }

    public function columns(): array
    {
        return [
            Column::make("Id", "id")
                ->sortable(),
            Column::make("Agrochemical uuid", "agrochemical_uuid")
                ->sortable(),
            Column::make("Movement type", "movement_type")
                ->sortable(),
            Column::make("Date", "date")
                ->sortable(),
            Column::make("Description", "description")
                ->sortable(),
            Column::make("Quantity", "quantity")
                ->sortable(),
            Column::make("Created at", "created_at")
                ->sortable(),
            Column::make("Updated at", "updated_at")
                ->sortable(),
        ];
    }
}
