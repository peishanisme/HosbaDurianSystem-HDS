<?php

namespace App\Livewire\Module\UserManagement;

use App\Livewire\Forms\UserForm;
use App\Models\User;
use Livewire\Component;
use Livewire\Attributes\Title;

#[Title('Account Details')]
class UserProfileLivewire extends Component
{
    public User $user;
    public UserForm $form;
    public string $role = '';

    public function mount()
    {
        $this->user = auth()->user();
        $this->form->edit($this->user);
        $this->role = $this->user->roles->first()?->name;
    }
    public function render()
    {
        return view('livewire.module.user-management.user-profile-livewire');
    }
}
