<?php

namespace App\Livewire\Forms;

use Livewire\Form;
use App\Models\Buyer;
use Livewire\Attributes\Validate;
use App\DataTransferObject\BuyerDTO;
use App\Actions\SalesAndTransactions\CreateBuyerAction;
use App\Actions\SalesAndTransactions\UpdateBuyerAction;

class BuyerForm extends Form
{
    public ?string $company_name, $contact_name, $contact_number, $email, $address;
    public ?Buyer $buyer = null;

    public function rules(): array
    {
        return [
            'company_name'   => ['required', 'string', 'max:255'],
            'contact_name'   => ['nullable', 'string', 'max:255'],
            'contact_number' => ['required', 'string', 'max:50'],
            'email'          => ['nullable', 'email', 'max:255'],
            'address'        => ['nullable', 'string', 'max:255'],
        ];
    }

    public function edit(Buyer $buyer): void
    {
        $this->buyer = $buyer;
        $this->company_name = $buyer->company_name;
        $this->contact_name = $buyer->contact_name;
        $this->contact_number = $buyer->contact_number;
        $this->email = $buyer->email;
        $this->address = $buyer->address;
    }

     public function create(array $validatedData): void
    {
        app(CreateBuyerAction::class)->handle(BuyerDTO::fromArray($validatedData));
    }

    public function update($validatedData): void
    {
        app(UpdateBuyerAction::class)->handle($this->buyer,BuyerDTO::fromArray($validatedData));
    }

}
