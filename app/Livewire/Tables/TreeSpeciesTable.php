<?php

namespace App\Livewire\Tables;

use App\Models\Species;
use Rappasoft\LaravelLivewireTables\{Views\Column, DataTableComponent};

class TreeSpeciesTable extends DataTableComponent
{
    protected $model = Species::class;

    public function configure(): void
    {
        $this->setPrimaryKey('id')
            ->setSearchPlaceholder('Search Species')
            ->setEmptyMessage('No results found')
            // ->setConfigurableAreas([
            //     'toolbar-right-end' => [
            //         'livewire.components.modal-button',
            //         [
            //             'label' => 'Create Species',
            //             'dispatch' => 'reset-species',
            //             'target' => 'speciesModalLivewire',
            //             'permission' => 'create-species',
            //         ]
            //     ]
            //])
            ;
    }

    public function columns(): array
    {
        return [
            Column::make("ID", "id")
                ->hideIf(true),

            Column::make("Name", "name")
                ->sortable()
                ->searchable(),

            Column::make("Code", "code")
                ->sortable()
                ->searchable(),

            Column::make("Description", "description"),

            Column::make("Tree Count")
                ->label(fn($row) => $row->trees()->count() ?? 0)
                ->sortable(function ($query, $direction) {
                    return $query->withCount('trees')->orderBy('trees_count', $direction);
                }),

            // Column::make("Created at", "created_at")
            //     ->sortable(),

            // Column::make('Actions')
            //     ->label(fn($row, Column $column) => view('components.table-com-button', [
            //         'modal'     => 'speciesModalLivewire',
            //         'dispatch1' => 'edit-species',
            //         'label1'    => 'Edit',
            //         'dataField' => 'species',
            //         'data'      =>  $row->id,
            //         'icon2'     => 'bi bi-trash3',
            //         'dispatch2' => 'delete-species',
            //         'label2'    => 'Delete',
            //         'permission1' => 'edit-species',
            //         'permission2' => 'delete-species',  
            //     ]))->html()
            //     ->excludeFromColumnSelect(),
        ];
    }
}
