<?php

namespace App\Livewire\Tables;

use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;
use App\Models\Disease;

class TreeDiseaseListingTable extends DataTableComponent
{
    protected $model = Disease::class;

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
            Column::make("DiseaseName", "diseaseName")
                ->sortable()
                ->searchable(),
            Column::make("Symptoms", "symptoms")
                ->searchable(),
            Column::make("Affected Trees")
                ->label(fn($row) => $row->tree->count())
                ->sortable(
                    fn($query, $direction) =>
                    $query->withCount('tree')->orderBy('tree_count', $direction)
                ),
            Column::make("Remarks", "remarks")

        ];
    }
}
