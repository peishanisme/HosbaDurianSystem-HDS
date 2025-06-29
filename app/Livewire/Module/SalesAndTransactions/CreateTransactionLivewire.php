<?php

namespace App\Livewire\Module\SalesAndTransactions;

use App\Traits\SweetAlert;
use Exception;
use App\Models\Buyer;
use Livewire\Component;
use Livewire\Attributes\On;
use Livewire\Attributes\Title;
use App\Livewire\Forms\TransactionForm;

#[Title('Sales & Transactions')]
class CreateTransactionLivewire extends Component
{
    use SweetAlert;
    public array $buyerOptions = [];
    public TransactionForm $form;

    public function mount()
    {
        $this->buyerOptions = Buyer::all()->mapWithKeys(function ($buyer) {
            return [$buyer->uuid => $buyer->company_name];
        })->toArray();
    }

    public function create(){
        $validatedData = $this->form->validate();
        
        try {

            $this->form->create($validatedData);
            $this->alertSuccess('Transaction has been created successfully.');

        
        } catch (Exception $error) {

            $this->alertError($error->getMessage());
        
        }
    }

     #[On('redirect-to-index')]
    public function redirect_to_index()
    {
        $this->redirectIntended(
            default: route('sales.transaction.index', absolute: false),
            navigate: true
        );
    }

    public function render()
    {
        return view('livewire.module.sales-and-transactions.create-transaction-livewire');
    }
}
