<?php

namespace App\Livewire\Tables;

use App\Models\Tree;
use Illuminate\Database\Eloquent\Builder;
use Rappasoft\LaravelLivewireTables\Views\Column;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Filters\SelectFilter;
use Rappasoft\LaravelLivewireTables\Views\Columns\ViewComponentColumn;

class TreeListingTable extends DataTableComponent
{
    public function builder(): Builder
    {
        return Tree::query()
            ->with(['species', 'latestGrowthLog'])
            ->orderBy('trees.created_at', 'desc'); 
    }


    public function configure(): void
    {
        $this->setPrimaryKey('id')
            ->setSearchPlaceholder('Search Tree')
            ->setEmptyMessage('No results found');
        // ->setConfigurableAreas([
        //     'toolbar-right-end' => [
        //         'livewire.components.modal-button',
        //         [
        //             'label' => 'Create Tree',
        //             'dispatch' => 'reset-tree',
        //             'target' => 'treeModalLivewire',
        //             'permission' => 'create-tree',
        //         ]
        //     ]
        // ]);
    }

    public function filters(): array
    {
        return [
            'species' => SelectFilter::make('Species')
                ->options(['' => 'Any'] + Tree::with('species')->get()->pluck('species.name', 'species.id')->toArray())
                ->filter(fn(Builder $query, $value) => $query->whereHas('species', fn($query) => $query->where('id', $value))),
        ];
    }

    public function columns(): array
    {
        return [
            Column::make("ID", "id")
                ->hideIf(true),

            Column::make("Thumbnail", "thumbnail")
                ->hideIf(true),

            ViewComponentColumn::make('Tree Tag', 'tree_tag')
                ->component('components.table-primary-column')
                ->attributes(fn($value, $row, Column $column) => [
                    'thumbnail' => $row->thumbnail ?? 'default',
                    'title' => $value,
                    'route' => route('tree.show', $row->id),
                ])->searchable()
                ->sortable(),

            ViewComponentColumn::make('Species', 'species.name')
                ->component('table-badge')
                ->attributes(fn($value, $row, Column $column) => [
                    'badge' => 'badge-light-success',
                    'label' => $value,
                ]),

            // Column::make("Current Height (m)")
            //     ->label(fn($row) => optional($row->latestGrowthLog)->height ?? '-'),

            // Column::make("Current Diameter (m)")
            //     ->label(fn($row) => optional($row->latestGrowthLog)->diameter ?? '-'),

            Column::make("Planted At", "planted_at")
                ->sortable(),

            Column::make('Actions')
                ->label(fn($row, Column $column) => view('components.table-com-button', [
                    'modal'     => 'treeModalLivewire',
                    'dispatch1' => 'edit-tree',
                    'label1'    => 'Edit',
                    'dataField' => 'tree',
                    'data'      =>  $row->id,
                    'icon2'     => 'bi bi-trash3',
                    'dispatch2' => 'delete-tree',
                    'label2'    => 'Delete',
                    'permission1' => 'edit-tree',
                    'permission2' => 'delete-tree',
                ]))->html()
                ->excludeFromColumnSelect(),
        ];
    }
}
