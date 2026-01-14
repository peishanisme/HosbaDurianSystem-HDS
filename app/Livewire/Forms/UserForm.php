<?php

namespace App\Livewire\Forms;

use App\Traits\PhoneNumberTrait;
use Livewire\Form;
use App\Models\User;
use App\Rules\UniquePhoneRule;
use Illuminate\Validation\Rule;
use App\DataTransferObject\UserDTO;
use App\Actions\UserManagement\CreateUserAction;
use App\Actions\UserManagement\UpdateUserAction;

class UserForm extends Form
{
    use PhoneNumberTrait;
    public ?User $user = null;
    public ?string $name, $email, $phone, $role;
    public bool $is_active = true;

    protected function rules(): array
    {
        return [
            'name'            => ['required', 'string', 'max:255'],
            'email'           => ['required', 'email', 'max:255', Rule::unique('users', 'email')->ignore($this->user->id ?? null)],
            'role'            => ['required', Rule::exists('roles', 'id')],
            'is_active'       => ['required', 'boolean'],
            'phone'           => ['required', 'string', 'numeric', 'regex:/^1\d{8,9}$/',new UniquePhoneRule($this->user->id ?? null)],
        ];
    }

    public function edit(User $user): void
    {
        $this->user = $user;
        $this->name = $user->name;
        $this->email = $user->email;
        $this->phone = self::formatForDisplay($user->phone);
        $this->role = $user->roles->first()->id ?? null;
        $this->is_active = $user->is_active;
    }

    public function create(array $validatedData): void
    {
        app(CreateUserAction::class)->handle(UserDTO::fromArray($validatedData));
    }

    public function update($validatedData): void
    {
        app(UpdateUserAction::class)->handle($this->user, UserDTO::fromArray($validatedData));
    }
}
