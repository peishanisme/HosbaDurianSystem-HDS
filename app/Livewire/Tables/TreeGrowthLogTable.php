<?php

namespace App\Livewire\Tables;

use App\Models\Tree;
use App\Models\TreeGrowthLog;
use Illuminate\Database\Eloquent\Builder;
use Rappasoft\LaravelLivewireTables\Views\Column;
use Rappasoft\LaravelLivewireTables\DataTableComponent;

class TreeGrowthLogTable extends DataTableComponent
{
    public Tree $tree;

    public function builder(): Builder
    {
        return TreeGrowthLog::query()->where('tree_uuid', $this->tree->uuid);
    }

    public function configure(): void
    {
        $this->setPrimaryKey('id')
            // ->setSearchPlaceholder('Search Tree Growth Logs')
            ->setEmptyMessage(__('messages.no_results_found'))
            ->setDefaultSort('created_at', 'desc')
            ->setSearchDisabled();
    }

    public function columns(): array
    {
        return [
            Column::make("Id", "id")
                ->sortable()
                ->hideIf(true),
            // Column::make("Tree id", "tree_id")
            //     ->sortable(),
            Column::make("Tree uuid", "tree_uuid")
                ->sortable()
                ->hideIf(true),
            Column::make(__('messages.height'), "height")
                ->sortable(),
            Column::make(__('messages.diameter'), "diameter")
                ->sortable(),
            // Column::make("Photo", "photo")
            //     ->sortable(),
            Column::make(__('messages.created_at'), "created_at")
                ->sortable(),
        ];
    }
}
