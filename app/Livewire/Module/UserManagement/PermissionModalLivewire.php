<?php

namespace App\Livewire\Module\UserManagement;

use App\Actions\UserManagement\UpdatePermissionAction;
use Livewire\Component;
use App\Traits\SweetAlert;
use Livewire\Attributes\On;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Traits\AuthorizesRoleOrPermission;

class PermissionModalLivewire extends Component
{
    use AuthorizesRoleOrPermission, SweetAlert;
    protected $listeners = ['refreshComponent' => '$refresh'];

    public string $modalID = 'permissionModalLivewire', $modalTitle = 'Permission Details';
    public ?Role $role;
    public $permissions, $roleName;
    public array $selectedPermissions = [];
    public string $search = '';

    public function mount(): void
    {
        $this->authorizeRoleOrPermission(['edit-permissions']);

        $this->permissions = Permission::all();
    }

    public function resetInput(): void
    {
        $this->reset('search');
    }

    #[On('edit-permission')]
    public function edit($role)
    {
        $this->role = Role::findById($role);
        $this->roleName = $this->role->name;
        $this->selectedPermissions = $this->role->permissions->pluck('id')->toArray();
    }

    public function getFilteredPermissionsProperty()
    {
        if (empty($this->search)) {
            return $this->permissions;
        }

        return $this->permissions->filter(function ($permission) {
            return str_contains(strtolower($permission->name), strtolower($this->search));
        });
    }

    public function update()
    {
        try {
            $this->selectedPermissions = array_map('intval', $this->selectedPermissions);
            UpdatePermissionAction::handle($this->role, $this->selectedPermissions);

            $this->alertSuccess('Permission has been updated successfully.', $this->modalID);
        } catch (\Exception $e) {

            $this->alertError('Failed to update permissions: ' . $e->getMessage(), $this->modalID);
        }
    }

    public function render()
    {
        return view('livewire.module.user-management.permission-modal-livewire');
    }
}
