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
            ->setSearchPlaceholder(__('messages.search_transactions'))
            ->setEmptyMessage(__('messages.no_results_found'))
            ->setConfigurableAreas([
                'toolbar-right-end' => [
                    'components.table-com-button2',
                    [
                        'label1' => __('messages.create_transaction'),
                        'dispatch1' => 'reset-transaction',
                        'permission1' => 'create-sale',
                        'redirectUrl' => 'sales.transaction.create',
                        'label2' => __('messages.generate_report'),
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
            Column::make(__('messages.reference_id'), "reference_id")
                ->sortable()
                ->searchable(),
            Column::make("Buyer uuid", "buyer_uuid")
                ->sortable()
                ->hideIf(true),
            Column::make(__('messages.company_name'), "buyer.company_name")
                ->sortable()
                ->searchable()
                ->hideIf($this->buyer !== null),
            Column::make(__('messages.reference_id'), "buyer.reference_id")
                ->sortable()
                ->searchable()
                ->hideIf($this->buyer !== null),
            Column::make(__('messages.date'), "date")
                ->sortable(),
            Column::make(__('messages.total_price'), "total_price")
                ->sortable(),
            ViewComponentColumn::make(__('messages.status'), 'is_cancelled')
                ->component('table-badge')
                ->attributes(fn($value, $row, Column $column) => [
                    'badge' => $row->is_cancelled ?  'badge-light-danger' : 'badge-light-success',
                    'label' => $row->is_cancelled ? __('messages.cancelled') : __('messages.active'),
                ]),
            Column::make(__('messages.created_at'), "created_at")
                ->sortable(),
            Column::make(__('messages.actions'))
                ->label(fn($row, Column $column) => view('components.table-button', [
                    'modal'     => 'transactionDetailsModalLivewire',
                    'icon'      => 'bi-eye',
                    'dispatch'  => 'view-transaction',
                    'label'     => __('messages.view'),
                    'dataField' => 'transaction',
                    'data'      =>  $row->id,
                    // 'permission' => 'view-disease',
                ]))->html()
                ->excludeFromColumnSelect(),
        ];
    }
}
