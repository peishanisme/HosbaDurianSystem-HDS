<?php

namespace App\Livewire\Tables;

use Spatie\Permission\Models\Role;
use Illuminate\Database\Eloquent\Builder;
use Rappasoft\LaravelLivewireTables\Views\Columns\CountColumn;
use Rappasoft\LaravelLivewireTables\{DataTableComponent, Views\Column};

class RoleListingTable extends DataTableComponent
{
    public function builder(): Builder
    {
        return Role::query();
    }

    public function configure(): void
    {
        $this->setPrimaryKey('id')
            ->setSearchPlaceholder(__('messages.search_roles'))
        ->setEmptyMessage(__('messages.no_results_found'));
    }

    public function columns(): array
    {
        return [
            Column::make("Id", "id")
                ->hideIf(true),

            Column::make(__('messages.role_name'), "name")
                ->searchable()
                ->sortable(),

            CountColumn::make(__('messages.users_count'))
                ->setDataSource('users')
                ->sortable(),

            Column::make(__('messages.actions'))
                ->label(fn($row, Column $column) => view('components.table-button', [
                    'modal' => 'permissionModalLivewire',
                    'label' => __('messages.edit_permissions'),
                    'dispatch' => 'edit-permission',
                    'dataField' => 'role',
                    'data' => $row->id,
                    'disabled' => $row->name === 'Super-Admin' || $row->name === 'Worker',
                    'permission' => 'edit-permissions',
                ]))->html()
                ->excludeFromColumnSelect(),
        ];
    }
}
