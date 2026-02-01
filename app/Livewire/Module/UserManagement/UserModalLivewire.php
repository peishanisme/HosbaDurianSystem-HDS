<?php

namespace App\Livewire\Module\UserManagement;

use Exception;
use App\Models\User;
use Livewire\Component;
use App\Traits\SweetAlert;
use Livewire\Attributes\On;
use App\Livewire\Forms\UserForm;
use Spatie\Permission\Models\Role;

class UserModalLivewire extends Component
{
    use SweetAlert;
    protected $listeners = ['refreshComponent' => '$refresh'];

    public UserForm $form;
    public string $modalID = 'userModalLivewire', $modalTitle ;
    public array $roleOptions = [];

    public function mount(): void
    {
        $this->modalTitle = __('messages.user_details');
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
            $this->alertSuccess(__('messages.user_created_successfully'), $this->modalID);
        } catch (Exception $error) {

            $this->alertError(__('messages.user_creation_failed'), $this->modalID);
        }
    }

    public function update(): void
    {
        $validatedData = $this->form->validate();

        try {

            $this->form->update($validatedData);
            $this->alertSuccess(__('messages.user_updated_successfully'), $this->modalID);
        } catch (Exception $error) {

            $this->alertError(__('messages.user_updation_failed'), $this->modalID);
        }
    }

    public function render()
    {
        return view('livewire.module.user-management.user-modal-livewire');
    }
}
