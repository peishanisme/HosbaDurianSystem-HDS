<?php

namespace App\Livewire\Tables;

use App\Models\Label;
use App\Models\Tree;
use Illuminate\Database\Eloquent\Builder;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;
use Rappasoft\LaravelLivewireTables\Views\Columns\ArrayColumn;
use Rappasoft\LaravelLivewireTables\Views\Columns\ViewComponentColumn;
use Rappasoft\LaravelLivewireTables\Views\Filters\DateFilter;
use Rappasoft\LaravelLivewireTables\Views\Filters\SelectFilter;

class TreeListingTable extends DataTableComponent
{
    public function builder(): Builder
    {
        return Tree::query()
            ->select('trees.*')
            ->with(['species', 'latestGrowthLog', 'latestLabel', 'activeObservation', 'labels']);
    }


    public function configure(): void
    {
        $this->setPrimaryKey('id')
            ->setSearchPlaceholder(__('messages.search_trees'))
            ->setEmptyMessage(__('messages.no_results_found'))
            ->setDefaultSort('id', 'asc')
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
            'planted_from' => DateFilter::make('Planted From')
                ->filter(
                    fn(Builder $query, $value) =>
                    $query->whereDate('planted_at', '>=', $value)
                ),

            'planted_to' => DateFilter::make('Planted To')
                ->filter(
                    fn(Builder $query, $value) =>
                    $query->whereDate('planted_at', '<=', $value)
                ),

            'species' => SelectFilter::make(__('messages.species'))
                ->options(['' => __('messages.any')] + Tree::with('species')->get()->pluck('species.name', 'species.id')->toArray())
                ->filter(
                    fn(Builder $query, $value) =>
                    $query->whereHas('species', fn($q) => $q->where('id', $value))
                ),

            'label' => SelectFilter::make(__('messages.label'))
                ->options(['' => __('messages.any')] + Label::pluck('name', 'id')->toArray())
                ->filter(
                    fn(Builder $query, $value) =>
                    $query->whereHas(
                        'labels',
                        fn($q) => $q->where('label_id', $value)
                    )
                ),

            'flowering_status' => SelectFilter::make(__('messages.flowering_status'))
                ->options([
                    '' => __('messages.any'),
                    'A' => 'A',
                    'B' => 'B',
                    'C' => 'C',
                    'D' => 'D',
                    'X' => 'X',
                ])
                ->filter(
                    fn(Builder $query, $value) =>
                    $query->whereHas(
                        'activeObservation',
                        fn($q) =>
                        $q->where('flowering_status', $value)
                    )
                ),

            'flowering_period' => SelectFilter::make(__('messages.flowering_period'))
                ->options([
                    '' => __('messages.any')
                ] + Tree::distinct()->pluck('flowering_period', 'flowering_period')->sort()->toArray())
                ->filter(
                    fn(Builder $query, $value) =>
                    $query->where('flowering_period', $value)
                ),

            'area' => SelectFilter::make(__('messages.area'))
                ->options(['' => __('messages.any')] + Tree::distinct()->pluck('area', 'area')->sort()->toArray())
                ->filter(
                    fn(Builder $query, $value) =>
                    $query->where('area', $value)
                ),

            'terrace' => SelectFilter::make(__('messages.terrace'))
                ->options(['' => __('messages.any')] + Tree::distinct()->pluck('terrace', 'terrace')->sort()->toArray())
                ->filter(
                    fn(Builder $query, $value) =>
                    $query->where('terrace', $value)
                ),

            'water_valve' => SelectFilter::make(__('messages.water_valve'))
                ->options(['' => __('messages.any')] + Tree::distinct()->pluck('water_valve', 'water_valve')->sort()->toArray())
                ->filter(
                    fn(Builder $query, $value) =>
                    $query->where('water_valve', $value)
                ),
        ];
    }

    public function columns(): array
    {
        return [
            Column::make("ID", "id")
                ->sortable()
                ->hideIf(true),

            Column::make("Thumbnail", "thumbnail")
                ->hideIf(true),

            ViewComponentColumn::make(__('messages.tree_tag'), 'tree_tag')
                ->component('components.table-primary-column')
                ->attributes(fn($value, $row, Column $column) => [
                    // 'avatar' => strtoupper(substr(trim($value), -4)),
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

            ArrayColumn::make(__('messages.label'))
                ->data(fn($value, $row) => $row->labels)
                ->outputFormat(fn($index, $value) => "<span class='badge badge-light' style='background-color: $value->color; color: #fff; margin-top: 0.5rem;'>$value->name</span>")
                ->html(),

            ViewComponentColumn::make(__('messages.flowering_status'), 'activeObservation.flowering_status')
                ->component('table-badge')
                ->sortable()
                ->attributes(fn($value, $row, Column $column) => [
                    'badge' => match ($value) {
                        'A' => 'badge-light-danger',
                        'B' => 'badge-light-warning',
                        'C' => 'badge-light-primary',
                        'D' => 'badge-light-info',
                        default => 'badge-light-secondary',
                    },
                    'label' => $value ?? 'X',
                ]),

            Column::make(__('messages.flowering_period'), "flowering_period")
                ->sortable(),

            Column::make(__('messages.area'), "area")
                ->sortable(),

            Column::make(__('messages.terrace'), "terrace")
                ->sortable(),

            Column::make(__('messages.water_valve'), "water_valve")
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
