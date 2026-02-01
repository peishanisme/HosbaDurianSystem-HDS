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
        $this->setPrimaryKey('id')
            ->setEmptyMessage(__('messages.no_results_found'))
            ->setSearchPlaceholder(__('messages.search_diseases'));
    }

    public function columns(): array
    {
        return [
            Column::make("Id", "id")
                ->sortable()
                ->hideIf(true),
            Column::make(__('messages.disease_name'), "diseaseName")
                ->sortable()
                ->searchable(),
            Column::make(__('messages.symptoms'), "symptoms")
                ->searchable(),
            Column::make(__('messages.affected_trees'))
                ->label(fn($row) => $row->trees()->distinct('tree_uuid')->count())
                ->sortable(
                    fn($query, $direction) =>
                    $query->withCount('trees', fn($q) => $q->distinct('tree_uuid'))->orderBy('trees_count', $direction)
                ),
           
            Column::make(__('messages.remarks'), "remarks"),
            Column::make(__('messages.actions'))
                ->label(fn($row, Column $column) => view('components.table-button', [
                    'modal'     => 'diseaseDetailsModalLivewire',
                    'icon'      => 'bi-eye',
                    'dispatch'  => 'view-disease',
                    'label'     => __('messages.view'),
                    'dataField' => 'disease',
                    'data'      =>  $row->id,
                    // 'permission' => 'view-disease',
                ]))->html()
                ->excludeFromColumnSelect(),

        ];
    }
}
