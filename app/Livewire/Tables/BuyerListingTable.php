<?php

namespace App\Livewire\Tables;

use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;
use App\Models\Buyer;

class BuyerListingTable extends DataTableComponent
{
    protected $model = Buyer::class;

    public function configure(): void
    {
        $this->setPrimaryKey('id')
            ->setSearchPlaceholder('Search Buyer')
            ->setEmptyMessage('No results found')
            ->setConfigurableAreas([
                'toolbar-right-end' => [
                    'livewire.components.modal-button',
                    [
                        'label' => 'Create Buyer',
                        'dispatch' => 'reset-buyer',
                        'target' => 'buyerModalLivewire'
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
            Column::make("Company name", "company_name")
                ->sortable(),
            Column::make("Contact name", "contact_name")
                ->sortable(),
            Column::make("Contact number", "contact_number")
                ->sortable(),
            Column::make("Created at", "created_at")
                ->sortable(),
            Column::make('Actions')
                ->label(fn($row, Column $column) => view('components.table-com-button', [
                    'modal'     => 'speciesModalLivewire',
                    'dispatch1' => 'edit-species',
                    'label1'    => 'Edit',
                    'dataField' => 'species',
                    'data'      =>  $row->id,
                    'icon2'     => 'bi bi-trash3',
                    'dispatch2' => 'delete-species',
                    'label2'    => 'Delete',
                ]))->html()
                ->excludeFromColumnSelect(),
        ];
    }
}
