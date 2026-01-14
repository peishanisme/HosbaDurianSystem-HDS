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
                ->label(fn($row) => $row->trees()->distinct('tree_uuid')->count())
                ->sortable(
                    fn($query, $direction) =>
                    $query->withCount('trees', fn($q) => $q->distinct('tree_uuid'))->orderBy('trees_count', $direction)
                ),
           
            Column::make("Remarks", "remarks"),
            Column::make('Actions')
                ->label(fn($row, Column $column) => view('components.table-button', [
                    'modal'     => 'diseaseDetailsModalLivewire',
                    'icon'      => 'bi-eye',
                    'dispatch'  => 'view-disease',
                    'label'     => 'View',
                    'dataField' => 'disease',
                    'data'      =>  $row->id,
                    // 'permission' => 'view-disease',
                ]))->html()
                ->excludeFromColumnSelect(),

        ];
    }
}
