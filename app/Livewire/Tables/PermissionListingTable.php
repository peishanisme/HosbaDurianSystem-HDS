<?php

namespace App\Livewire\Tables;

use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Database\Eloquent\Builder;
use Rappasoft\LaravelLivewireTables\Views\Columns\ArrayColumn;
use Rappasoft\LaravelLivewireTables\Views\Filters\SelectFilter;
use Rappasoft\LaravelLivewireTables\{DataTableComponent, Views\Column};

class PermissionListingTable extends DataTableComponent
{
    public function builder(): Builder
    {
        return Permission::query()
            ->with('roles');
    }
    public function configure(): void
    {
        $this->setPrimaryKey('id')
        ->setDefaultSort('created_at', 'desc')
        ->setSearchPlaceholder(__('messages.search_permission'))
        ->setEmptyMessage(__('messages.no_results_found'));
    }

    public function filters(): array
    {
        return [
            'role' => SelectFilter::make(__('messages.role'))
                ->options(['' => __('messages.any')] + Role::pluck('name','id')->toArray())
                ->filter(fn(Builder $query, $value) => $query->whereHas('roles', fn($query) => $query->where('id', $value))),
        ];
    }

    public function columns(): array
    {
        return [
            Column::make("Id", "id")
                ->hideIf(true),

            Column::make(__('messages.name'), "name")
                ->searchable()
                ->sortable(),

            ArrayColumn::make(__('messages.roles'))
                ->data(fn($value, $row) => $row->roles)
                ->outputFormat(fn($index, $value) => "<span class='badge badge-light-primary mt-3'>$value->name</span>")
                ->sortable(),

            Column::make(__('messages.created_at'), "created_at")
                ->sortable(),

        ];
    }
}
