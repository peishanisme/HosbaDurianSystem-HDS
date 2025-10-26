<?php

namespace App\Livewire\Module\UserManagement;

use App\Models\User;
use Livewire\Component;
use App\Traits\SweetAlert;
use Livewire\Attributes\Title;
use App\Livewire\Forms\UserForm;
use Illuminate\Support\Facades\Hash;
use App\Actions\FormatPhoneNumberAction;

#[Title('Account Details')]
class UserProfileLivewire extends Component
{
    use SweetAlert;
    public User $user;
    public UserForm $form;
    public string $role = '';
    public string $phone ='';
    // Password fields
    public string $old_password = '';
    public string $new_password = '';
    public string $confirm_password = '';

    public function mount()
    {
        $this->user = auth()->user();
        $this->form->edit($this->user);
        $this->role = $this->user->roles->first()?->name;
        $this->phone = preg_replace('/^\+?60/', '', $this->user->phone);
    }

    /** -----------------------------
     * Update profile details
     * ----------------------------- */
    public function update(): void
    {
        $this->form->phone = FormatPhoneNumberAction::handle($this->phone);
        $validatedData = $this->form->validate();

        try {

            $this->form->update($validatedData);
            $this->alertSuccess('Your profile has been updated successfully.');
        
        } catch (\Exception $error) {

            $this->alertError($error->getMessage());
        
        }
    }

    /** -----------------------------
     * Update password
     * ----------------------------- */
    public function updatePassword(): void
    {
        $this->validate([
            'old_password' => ['required', 'current_password'],
            'new_password' => ['required', 'string', 'min:8'],
            'confirm_password' => ['required', 'string', 'min:8', 'same:new_password'],
        ], [
            'old_password.current_password' => 'Your old password is incorrect.',
        ]);

        try {
            $this->user->update([
                'password' => Hash::make($this->new_password),
            ]);

            // Clear password fields after success
            $this->reset(['old_password', 'new_password', 'confirm_password']);

            $this->alertSuccess('Your password has been changed successfully.');
        } catch (\Exception $error) {
            $this->alertError($error->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.module.user-management.user-profile-livewire');
    }
}
