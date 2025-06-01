<?php

namespace App\Livewire\Forms;

use Livewire\Form;
use App\Models\User;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Validate;
use App\Actions\UserManagement\CreateUserAction;
use App\DataTransferObject\UserDTO;

class UserForm extends Form
{
    public ?User $user = null;
    public ?string $name, $email, $phone, $role;
    public bool $is_active = true;

    protected function rules(): array
    {
        return [
            'name'            => ['required', 'string', 'max:255'],
            'email'           => ['nullable', 'email', 'max:255', Rule::unique('users', 'email')->ignore($this->user->id ?? null)],
            'role'            => ['required', Rule::exists('roles', 'id')],
            'is_active'       => ['required', 'boolean'],
            'phone'           => ['required', 'string', Rule::unique('users', 'phone')->ignore($this->user->id ?? null)],
        ];
    }

    public function edit(User $user): void
    {
        $this->user = $user;
        $this->name = $user->name;
        $this->email = $user->email;
        $this->phone = $user->phone;
        $this->role = $user->getRoleNames()->first();
        $this->is_active = $user->is_active;
    }

     public function create(array $validatedData): void
    {
        app(CreateUserAction::class)->handle(UserDTO::fromArray($validatedData));
    }

    
}
