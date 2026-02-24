<?php

namespace App\Livewire\Tables;

use App\Models\Buyer;
use Illuminate\Database\Eloquent\Builder;
use Rappasoft\LaravelLivewireTables\Views\Column;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Columns\ViewComponentColumn;

class BuyerListingTable extends DataTableComponent
{
    protected $model = Buyer::class;

    public function configure(): void
    {
        $this->setPrimaryKey('id')
            ->setSearchPlaceholder(__('messages.search_buyers'))
            ->setEmptyMessage(__('messages.no_results_found'))
            ->setConfigurableAreas([
                'toolbar-right-end' => [
                    'livewire.components.modal-button',
                    [
                        'label' => __('messages.create_buyer'),
                        'dispatch' => 'reset-buyer',
                        'target' => 'buyerModalLivewire',
                        'permission' => 'create-buyer',
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

            ViewComponentColumn::make(__('messages.company_name'), 'company_name')
                ->component('components.table-primary-column')
                ->attributes(fn($value, $row, Column $column) => [
                    'avatar' => $value,
                    'title' => $value,
                    'route' => route('sales.buyers.show', $row->id),
                ])->searchable()
                ->sortable(),
            Column::make(__('messages.reference_id'), "reference_id")
                ->sortable()
                ->searchable(),
            Column::make(__('messages.contact_name'), "contact_name")
                ->sortable()
                ->searchable(),
            Column::make(__('messages.contact_number'), "contact_number")
                ->sortable(),
            // Column::make("Total Sales (RM)")
            //     ->label(fn($row, Column $column) => number_format(
            //         $row->transactions->sum('amount'),
            //         2
            //     ))
            //     ->sortable(
            //         fn(Builder $query, string $direction) => $query->withSum(
            //             ['transactions' => function ($query) {
            //                 $query->where('type', 'sale');
            //             }],
            //             'deposit'
            //         )->orderBy('transactions_sum_deposit', $direction)
            //     ),
            Column::make(__('messages.created_at'), "created_at")
                ->sortable(),
            Column::make(__('messages.actions'))
                ->label(fn($row, Column $column) => view('components.table-com-button', [
                    'modal'     => 'buyerModalLivewire',
                    'dispatch1' => 'edit-buyer',
                    'label1'    => __('messages.edit'),
                    'dataField' => 'buyer',
                    'data'      =>  $row->id,
                    'icon2'     => 'bi bi-trash3',
                    'dispatch2' => 'delete-buyer',
                    'label2'    => __('messages.delete'),
                    'permission1' => 'edit-buyer',
                    'permission2' => 'delete-buyer',
                ]))->html()
                ->excludeFromColumnSelect(),
        ];
    }
}
