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
        ->setEmptyMessage('No results found');
    }

    public function filters(): array
    {
        return [
            'role' => SelectFilter::make('Role')
                ->options(['' => 'Any'] + Role::pluck('name','id')->toArray())
                ->filter(fn(Builder $query, $value) => $query->whereHas('roles', fn($query) => $query->where('id', $value))),
        ];
    }

    public function columns(): array
    {
        return [
            Column::make("Id", "id")
                ->hideIf(true),

            Column::make("Name", "name")
                ->searchable()
                ->sortable(),

            ArrayColumn::make('Roles')
                ->data(fn($value, $row) => $row->roles)
                ->outputFormat(fn($index, $value) => "<span class='badge badge-light-primary mt-3'>$value->name</span>")
                ->sortable(),

            Column::make("Created At", "created_at")
                ->sortable(),

        ];
    }
}
