<?php

namespace App\Livewire\Tables;

use Spatie\Activitylog\Models\Activity;
use Illuminate\Database\Eloquent\Builder;
use Rappasoft\LaravelLivewireTables\{DataTableComponent, Views\Column, Views\Columns\ViewComponentColumn};

class ActivityLogTable extends DataTableComponent
{
    public function builder(): Builder
    {
        return Activity::query()
            ->select('activity_log.*', 'users.name as causer_name')
            ->leftJoin('users', 'activity_log.causer_id', '=', 'users.id');
    }

    public function configure(): void
    {
        $this->setPrimaryKey('id')
            ->setEmptyMessage('No results found')
            ->setDefaultSort('created_at', 'desc');
    }


    public function columns(): array
    {
        return [
            Column::make("Id", "id")
                ->hideIf(true),

            Column::make('Description', 'description')
                ->searchable(),

            Column::make('Subject', 'log_name')
                ->searchable()
                ->sortable(),

            Column::make('Action By', 'causer_id')
                ->format(fn($value, $row, Column $column) => $row->causer_name)
                ->searchable()
                ->sortable(),

            Column::make('Created At', 'created_at')
                ->format(fn($value) => \Carbon\Carbon::parse($value)->diffForHumans())
                ->sortable(),

            ViewComponentColumn::make('Properties', 'properties')
                ->component('components.popovers')
                ->attributes(fn($value, $row, Column $column) => [
                    'button' => 'Properties',
                    'content' => $value
                ]),
        ];
    }
}
