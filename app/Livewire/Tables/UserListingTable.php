<?php

namespace App\Livewire\Tables;

use App\Models\User;
use Spatie\Permission\Models\Role;
use Illuminate\Database\Eloquent\Builder;
use Rappasoft\LaravelLivewireTables\Views\Column;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Columns\ArrayColumn;
use Rappasoft\LaravelLivewireTables\Views\Filters\SelectFilter;
use Rappasoft\LaravelLivewireTables\Views\Columns\ViewComponentColumn;

class UserListingTable extends DataTableComponent
{
    protected $model = User::class;

    public function configure(): void
    {
        $this->setPrimaryKey('id')
            ->setSearchPlaceholder('Search User')
            ->setEmptyMessage('No results found')
            ->setConfigurableAreas([
                'toolbar-right-end' => [
                    'livewire.components.modal-button',
                    [
                        'label' => 'Create User',
                        'dispatch' => 'reset-user',
                        'target' => 'userModalLivewire'
                    ]
                ]
            ]);
    }

    public function filters(): array
    {
        return [
            'active' => SelectFilter::make('Status')
                ->options([
                    '' => 'Any',
                    'yes' => 'Active',
                    'no' => 'Inactive',
                ])->filter(fn(Builder $query, $value) => $query->where('is_active', $value === 'yes')),

            'role' => SelectFilter::make('Role')
                ->options(['' => 'Any'] + Role::pluck('name', 'id')->toArray())
                ->filter(fn(Builder $query, $value) => $query->whereHas('roles', fn($query) => $query->where('id', $value))),
        ];
    }

    public function columns(): array
    {
        return [
            Column::make("ID", "id")
                ->hideIf(true),

            Column::make("Name", "name")
                ->sortable(),

            Column::make("Email", "email")
                ->sortable(),

            Column::make("Phone", "phone")
                ->sortable(),

            ArrayColumn::make('Roles')
                ->data(fn($value, $row) => $row->roles)
                ->outputFormat(fn($index, $value) => "<span class='badge badge-light-primary'>$value->name</span>"),

            ViewComponentColumn::make('Status', 'is_active')
                ->component('table-badge')
                ->attributes(fn($value, $row, Column $column) => [
                    'badge' => $row->is_active ?  'badge-light-success' : 'badge-light-danger',
                    'label' => $row->is_active ? 'Active' : 'Inactive',
                ]),

            Column::make("Created at", "created_at")
                ->sortable(),

            Column::make('Actions')
                ->label(fn($row, Column $column) => view('components.table-button', [
                    'modal' => 'userModalLivewire',
                    'disabled' => $row->roles->contains('name', 'Super-Admin'),
                    'dispatch' => 'edit-user',
                    'dataField' => 'user',
                    'data' => $row->id,
                    'permission' => 'edit-user',
                ]))->html()
                ->excludeFromColumnSelect(),
        ];
    }
}
