<?php

namespace App\Livewire\UserManagement;

use App\Traits\SweetAlert;
use Exception;
use App\Models\User;
use Livewire\Component;
use Livewire\Attributes\On;
use App\Livewire\Forms\UserForm;
use Spatie\Permission\Models\Role;

class UserModalLivewire extends Component
{
    use SweetAlert;
    public UserForm $form;
    public string $modalID = 'userModalLivewire', $modalTitle = 'User Details';
    public array $roleOptions = [];

    public function mount(): void
    {
        $this->roleOptions = Role::whereNotIn('name', ['Super-Admin'])
            ->pluck('name', 'id')
            ->toArray();
    }

    #[On('reset-user')]
    public function resetInput()
    {
        $this->form->resetValidation();
        $this->form->reset();
    }

    #[On('edit-user')]
    public function edit(User $user): void
    {
        $this->resetInput();
        $this->form->edit($user);
    }

    public function create(): void
    {
        $validatedData = $this->form->validate();
        
        try {

            $this->form->create($validatedData);
            $this->alertSuccess('User has been created successfully.', $this->modalID);

        
        } catch (Exception $error) {

            $this->alertError($error->getMessage(), $this->modalID);
        
        }
    }

    public function update(): void
    {
        $validatedData = $this->form->validate();
        
        try {

            $this->form->update($validatedData);
            $this->alertSuccess('User has been updated successfully.', $this->modalID);

        
        } catch (Exception $error) {

            $this->alertError($error->getMessage(), $this->modalID);
        
        }
    }

    public function render()
    {
        return view('livewire.user-management.user-modal-livewire');
    }
}
