<?php

namespace App\Livewire\Tables;

use App\Models\Tree;
use App\Models\Disease;
use App\Models\HealthRecord;
use Illuminate\Database\Eloquent\Builder;
use Rappasoft\LaravelLivewireTables\Views\Column;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Filters\SelectFilter;
use Rappasoft\LaravelLivewireTables\Views\Columns\ViewComponentColumn;

class TreeHealthRecordTable extends DataTableComponent
{
    public Tree $tree;
    public function builder(): Builder
    {
        return HealthRecord::query()
            ->where('tree_uuid', $this->tree->uuid)
            ->with('disease');
    }

    public function configure(): void
    {
        $this->setPrimaryKey('id')
            ->setDefaultSort('recorded_at', 'desc');
    }

    public function filters(): array
    {
        return [
            SelectFilter::make('Status')
                ->options([
                    '' => 'All',
                    'Severe' => 'Severe',
                    'Medium' => 'Medium',
                    'Recovered' => 'Recovered',
                ])
                ->filter(function (Builder $builder, string $value) {
                    if ($value !== '') {
                        $builder->where('status', $value);
                    }
                }),
        ];
    }

    public function columns(): array
    {
        return [
            Column::make("Id", "id")
                ->sortable()
                ->hideIf(true),
            Column::make("Tree uuid", "tree_uuid")
                ->sortable()
                ->hideIf(true),
            Column::make("Disease id", "disease_id")
                ->sortable()
                ->hideIf(true),
            Column::make("Thumbnail", "thumbnail")
                ->sortable()
                ->hideIf(true),
            ViewComponentColumn::make('Disease', 'disease.diseaseName')
                ->component('components.table-primary-column')
                ->attributes(fn($value, $row, Column $column) => [
                    'thumbnail' => $row->thumbnail ?? 'default',
                    'title' => $value,
                ])->searchable()
                ->sortable(),
            Column::make("Status", "status")
                ->format(fn($value) => match ($value) {
                    'Severe' => '<span class="badge badge-light-danger">Severe</span>',
                    'Medium' => '<span class="badge badge-light-warning">Medium</span>',
                    'Recovered' => '<span class="badge badge-light-success">Recovered</span>',
                    default => '-',
                })
                ->html()
                ->sortable(),
            Column::make("Treatment", "treatment")
                ->format(fn($value) => $value ?? '-'),
            Column::make("Recorded At", "recorded_at")
                ->format(fn($value) => $value ? date('d M Y', strtotime($value)) : '-')
                ->sortable(),
            Column::make("Updated At", "updated_at")
                ->format(fn($value) => date('d M Y, h:i A', strtotime($value)))
                ->sortable(),
        ];
    }
}
