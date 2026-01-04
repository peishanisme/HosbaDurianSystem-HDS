<?php

namespace App\Livewire\Tables;

use App\Models\Agrochemical;
use App\Models\AgrochemicalRecord;
use Illuminate\Database\Eloquent\Builder;
use Rappasoft\LaravelLivewireTables\Views\Column;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Columns\ViewComponentColumn;

class AgrochemicalApplicationRecordTable extends DataTableComponent
{
    public ?Agrochemical $agrochemical = null;

    public function builder(): Builder
    {
        return AgrochemicalRecord::where('agrochemical_uuid', $this->agrochemical?->uuid)
            ->with('agrochemical', 'tree');
    }

    public function configure(): void
    {
        $this->setPrimaryKey('id');
    }

    public function columns(): array
    {
        return [
            Column::make("Id", "id")
                ->sortable()
                ->hideIf(true),
            Column::make("Agrochemical uuid", "agrochemical_uuid")
                ->sortable()
                ->hideIf(true),
            Column::make("Tree uuid", "tree_uuid")
                ->sortable()
                ->hideIf(true),
            ViewComponentColumn::make('Tree Tag', 'tree.tree_tag')
                ->component('components.table-primary-column')
                ->attributes(fn($value, $row, Column $column) => [
                    'thumbnail' => $row->tree->thumbnail ?? 'default',
                    'title' => $value,
                    'route' => route('tree.show', $row->tree->id),
                ])->searchable()
                ->sortable(),
            Column::make("Species", "tree.species.name")
                ->sortable(),
            Column::make("Applied at", "applied_at")
                ->sortable(),
            Column::make("Description", "description"),
            Column::make("Created at", "created_at")
                ->sortable(),
            // Column::make("Updated at", "updated_at")
            //     ->sortable(),
        ];
    }
}
