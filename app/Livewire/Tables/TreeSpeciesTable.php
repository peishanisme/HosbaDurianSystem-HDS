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
            ->setSearchPlaceholder(__('messages.search_species'))
            ->setEmptyMessage(__('messages.no_results_found'))
            ;
    }

    public function columns(): array
    {
        return [
            Column::make("ID", "id")
                ->hideIf(true),

            Column::make(__('messages.name'), "name")
                ->sortable()
                ->searchable(),

            Column::make(__('messages.code'), "code")
                ->sortable()
                ->searchable(),

            Column::make(__('messages.description'), "description"),
            Column::make(__('messages.tree_count'))
                ->label(fn($row) => $row->trees()->count() ?? 0)
                ->sortable(function ($query, $direction) {
                    return $query->withCount('trees')->orderBy('trees_count', $direction);
                }),
        ];
    }
}
