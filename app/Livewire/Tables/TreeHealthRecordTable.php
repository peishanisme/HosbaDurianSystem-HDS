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
            ->setEmptyMessage(__('messages.no_results_found'))
            ->setSearchPlaceholder(__('messages.search_health_records'))
            ->setDefaultSort('recorded_at', 'desc');
    }

    public function filters(): array
    {
        return [
            SelectFilter::make(__('messages.status'))
                ->options([
                    '' => __('messages.any'),
                    'Severe' => __('messages.severe'),
                    'Medium' => __('messages.medium'),
                    'Recovered' => __('messages.recovered'),
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
            ViewComponentColumn::make(__('messages.disease_name'), 'disease.diseaseName')
                ->component('components.table-primary-column')
                ->attributes(fn($value, $row, Column $column) => [
                    'thumbnail' => $row->thumbnail ?? 'default',
                    'title' => $value,
                ])->searchable()
                ->sortable(),
            Column::make(__('messages.status'), "status")
                ->format(fn($value) => match ($value) {
                    'Severe' => '<span class="badge badge-light-danger">'.__('messages.severe').'</span>',
                    'Medium' => '<span class="badge badge-light-warning">'.__('messages.medium').'</span>',
                    'Recovered' => '<span class="badge badge-light-success">'.__('messages.recovered').'</span>',
                    default => '-',
                })
                ->html()
                ->sortable(),
            Column::make(__('messages.treatment'), "treatment")
                ->format(fn($value) => $value ?? '-'),
            Column::make(__('messages.recorded_at'), "recorded_at")
                ->format(fn($value) => $value ? date('d M Y', strtotime($value)) : '-')
                ->sortable(),
            Column::make(__('messages.updated_at'), "updated_at")
                ->format(fn($value) => date('d M Y, h:i A', strtotime($value)))
                ->sortable(),
        ];
    }
}
