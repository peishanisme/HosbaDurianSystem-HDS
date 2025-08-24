<?php

namespace App\Livewire\Tables;

use App\Models\Transaction;
use Illuminate\Database\Eloquent\Builder;
use Rappasoft\LaravelLivewireTables\Views\Column;
use Rappasoft\LaravelLivewireTables\DataTableComponent;

class TransactionListingTable extends DataTableComponent
{
    protected $model = Transaction::class;

    public function builder(): Builder
    {
        return Transaction::query()->with('buyer');
    }

    public function configure(): void
    {
        $this->setPrimaryKey('id')
            ->setSearchPlaceholder('Search Transaction')
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
                ->sortable()
                ->hideIf(true),
            Column::make("Uuid", "uuid")
                ->sortable()
                ->hideIf(true),
            Column::make("Reference ID", "reference_id")
                ->sortable()
                ->searchable(),
            Column::make("Buyer uuid", "buyer_uuid")
                ->sortable()
                ->hideIf(true),
            Column::make("Buyer", "buyer.company_name")  
                ->sortable()
                ->searchable(),
            Column::make("Buyer Ref ID", "buyer.reference_id")
                ->sortable()
                ->searchable(),
            Column::make("Date", "date")
                ->sortable(),
            Column::make("Total price", "total_price")
                ->sortable(),
            Column::make("Created at", "created_at")
                ->sortable(),
        ];
    }
}
