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
        ->setEmptyMessage('No results found');
    }

    public function columns(): array
    {
        return [
            Column::make("Id", "id")
                ->hideIf(true),

            Column::make("Name", "name")
                ->searchable()
                ->sortable(),

            CountColumn::make('Users Count')
                ->setDataSource('users')
                ->sortable(),

            Column::make('Actions')
                ->label(fn($row, Column $column) => view('components.table-button', [
                    'modal' => 'permissionModalLivewire',
                    'label' => 'Edit Permissions',
                    'dispatch' => 'edit-permission',
                    'dataField' => 'role',
                    'data' => $row->id,
                    'disabled' => $row->name === 'Super-Admin',
                    'permission' => 'edit-permissions',
                ]))->html()
                ->excludeFromColumnSelect(),
        ];
    }
}
