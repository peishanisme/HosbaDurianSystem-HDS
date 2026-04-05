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
            ->setSearchPlaceholder(__('messages.search_trees'))
            ->setEmptyMessage(__('messages.no_results_found'))
            ->setConfigurableAreas([
                'toolbar-right-end' => [
                    'livewire.components.modal-button',
                    [
                        'label' => __('messages.create_tree'),
                        'dispatch' => 'reset-tree',
                        'target' => 'treeModalLivewire',
                        'permission' => 'create-tree',
                    ]
                ]
            ]);
    }

    public function filters(): array
    {
        return [
            'species' => SelectFilter::make(__('messages.species'))
                ->options(['' => __('messages.any')] + Tree::with('species')->get()->pluck('species.name', 'species.id')->toArray())
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

            ViewComponentColumn::make(__('messages.tree_tag'), 'tree_tag')
                ->component('components.table-primary-column')
                ->attributes(fn($value, $row, Column $column) => [
                    'avatar' => strtoupper(substr(trim($value), -4)),
                    'title' => $value,
                    'route' => route('tree.show', $row->id),
                ])->searchable()
                ->sortable(),

            ViewComponentColumn::make(__('messages.species'), 'species.name')
                ->component('table-badge')
                ->attributes(fn($value, $row, Column $column) => [
                    'badge' => 'badge-light-success',
                    'label' => $value,
                ]),

            Column::make(__('messages.planted_at'), "planted_at")
                ->sortable(),

            Column::make(__('messages.area'), "area")
                ->sortable(),

            Column::make(__('messages.terrace'), "terrace")
                ->sortable(),

            Column::make(__('messages.actions'))
                ->label(fn($row, Column $column) => view('components.table-button', [
                    'modal'     => 'treeModalLivewire',
                    'dispatch' => 'edit-tree',
                    'label'    => __('messages.edit'),
                    'dataField' => 'tree',
                    'data'      =>  $row->id,
                    'permission' => 'edit-tree',
                ]))->html()
                ->excludeFromColumnSelect(),
        ];
    }
}
