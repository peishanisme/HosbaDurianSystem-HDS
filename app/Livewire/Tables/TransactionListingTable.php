<?php

namespace App\Livewire\Tables;

use App\Models\Buyer;
use App\Models\Transaction;
use Illuminate\Database\Eloquent\Builder;
use Rappasoft\LaravelLivewireTables\Views\Column;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Columns\ViewComponentColumn;

class TransactionListingTable extends DataTableComponent
{
    public ?Buyer $buyer = null;
    protected $model = Transaction::class;

    public function builder(): Builder
    {
        $query = Transaction::query()->with('buyer');

        if ($this->buyer) {
            $query->where('buyer_uuid', $this->buyer->uuid);
        }

        return $query
            ->orderBy('is_cancelled', 'asc')
            ->orderBy('created_at', 'desc');
    }


    public function configure(): void
    {
        $this->setPrimaryKey('id')
            ->setSearchPlaceholder('Search Transaction')
            ->setEmptyMessage('No results found')
            ->setConfigurableAreas([
                'toolbar-right-end' => [
                    'components.table-com-button2',
                    [
                        'label1' => 'Create Transaction',
                        'dispatch1' => 'reset-transaction',
                        'permission1' => 'create-sale',
                        'redirectUrl' => 'sales.transaction.create',
                        'label2' => 'Generate Report',
                        'dispatch2' => 'reset-generator',
                        'target2' => 'generateReportModalLivewire',
                        'permission2' => 'export-reports',
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
            ViewComponentColumn::make('Status', 'is_cancelled')
                ->component('table-badge')
                ->attributes(fn($value, $row, Column $column) => [
                    'badge' => $row->is_cancelled ?  'badge-light-danger' : 'badge-light-success',
                    'label' => $row->is_cancelled ? 'Cancelled' : 'Active',
                ]),
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
