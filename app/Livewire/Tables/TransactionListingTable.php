<?php

namespace App\Livewire\Tables;

use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;
use App\Models\Transaction;

class TransactionListingTable extends DataTableComponent
{
    protected $model = Transaction::class;

    public function configure(): void
    {
        $this->setPrimaryKey('id')
            ->setSearchPlaceholder('Search Buyer')
            ->setEmptyMessage('No results found')
            ->setConfigurableAreas([
                'toolbar-right-end' => [
                    'components.redirect-button',
                    [
                        'label' => 'Create Transaction',
                        'dispatch' => 'reset-transaction',
                        'redirectUrl' => 'sales.transaction.create',
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
            Column::make("Buyer uuid", "buyer_uuid")
                ->sortable(),
            Column::make("Date", "date")
                ->sortable(),
            Column::make("Total price", "total_price")
                ->sortable(),
            Column::make("Created at", "created_at")
                ->sortable(),
            Column::make("Updated at", "updated_at")
                ->sortable(),
        ];
    }
}
