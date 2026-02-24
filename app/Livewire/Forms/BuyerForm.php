<?php

namespace App\Livewire\Forms;

use App\Traits\PhoneNumberTrait;
use Livewire\Form;
use App\Models\Buyer;
use App\DataTransferObject\BuyerDTO;
use App\Actions\SalesAndTransactions\CreateBuyerAction;
use App\Actions\SalesAndTransactions\UpdateBuyerAction;

class BuyerForm extends Form
{
    use PhoneNumberTrait;
    public ?string $company_name, $contact_name, $contact_number, $email, $address;
    public ?Buyer $buyer = null;

    public function rules(): array
    {
        return [
            'company_name'   => ['required', 'string', 'max:255','unique:buyers,company_name,' . ($this->buyer?->id ?? 'NULL')],
            'contact_name'   => ['nullable', 'string', 'max:255'],
            'contact_number' => ['required', 'string','numeric','regex:/^1\d{8,9}$/'],
            'email'          => ['nullable', 'email', 'max:255'],
            'address'        => ['nullable', 'string', 'max:255'],
        ];
    }

    public function edit(Buyer $buyer): void
    {
        $this->buyer = $buyer;
        $this->company_name = $buyer->company_name;
        $this->contact_name = $buyer->contact_name;
        $this->contact_number = self::formatForDisplay($buyer->contact_number);
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
