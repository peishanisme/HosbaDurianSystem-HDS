<?php

namespace App\Livewire\Tables;

use App\Models\Buyer;
use App\Models\Transaction;
use Illuminate\Database\Eloquent\Builder;
use Rappasoft\LaravelLivewireTables\Views\Column;
use Rappasoft\LaravelLivewireTables\DataTableComponent;

class TransactionListingTable extends DataTableComponent
{
    public ?Buyer $buyer = null;
    protected $model = Transaction::class;

    public function builder(): Builder
    {
        if ($this->buyer) {
            return Transaction::query()->where('buyer_uuid', $this->buyer->uuid)->with('buyer');
        }

        return Transaction::query()->with('buyer');
    }

    public function configure(): void
    {
        $this->setPrimaryKey('id')
            ->setSearchPlaceholder('Search Transaction')
            ->setEmptyMessage('No results found')
            ->setDefaultSort('created_at', 'desc')
            ->setConfigurableAreas([
                'toolbar-right-end' => [
                    'components.table-com-button2',
                    [
                        'label1' => 'Create Transaction',
                        'dispatch1' => 'reset-transaction',
                        'redirectUrl' => 'sales.transaction.create',
                        'label2' => 'Generate Report',
                        'dispatch2' => 'reset-generator',
                        'target2' => 'generateReportModalLivewire',
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
                ->searchable()
                ->hideIf($this->buyer !== null),
            Column::make("Buyer Ref ID", "buyer.reference_id")
                ->sortable()
                ->searchable()
                ->hideIf($this->buyer !== null),
            Column::make("Date", "date")
                ->sortable(),
            Column::make("Total price", "total_price")
                ->sortable(),
            Column::make("Created at", "created_at")
                ->sortable(),
            Column::make('Actions')
                ->label(fn($row, Column $column) => view('components.table-button', [
                    'modal'     => 'transactionDetailsModalLivewire',
                    'icon'      => 'bi-eye',
                    'dispatch'  => 'view-transaction',
                    'label'     => 'View',
                    'dataField' => 'transaction',
                    'data'      =>  $row->id,
                    // 'permission' => 'view-disease',
                ]))->html()
                ->excludeFromColumnSelect(),
        ];
    }
}
