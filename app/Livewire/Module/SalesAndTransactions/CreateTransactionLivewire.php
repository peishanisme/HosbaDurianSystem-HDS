<?php

namespace App\Livewire\Module\SalesAndTransactions;

use Exception;
use App\Models\Buyer;
use App\Models\Fruit;
use Livewire\Component;
use App\Traits\SweetAlert;
use Livewire\Attributes\On;
use Livewire\Attributes\Title;
use App\Livewire\Forms\TransactionForm;

#[Title('Sales & Transactions')]
class CreateTransactionLivewire extends Component
{
    use SweetAlert;
    public array $buyerOptions = [];
    public TransactionForm $form;
    public $scannedFruits = [];

    public function mount()
    {
        $this->buyerOptions = Buyer::all()->mapWithKeys(function ($buyer) {
            return [$buyer->uuid => $buyer->company_name];
        })->toArray();
    }

    public function create()
    {
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

    public function addScannedFruit($fruitTag)
    {
        $fruit = Fruit::where('tag', $fruitTag)->first();

        if (!$fruit) {
            $this->dispatchBrowserEvent('notify', ['message' => 'Fruit not found']);
            return;
        }

        $this->scannedFruits[] = [
            'tag' => $fruit->tag,
            'species' => $fruit->species,
            'grade' => $fruit->grade,
            'weight' => $fruit->weight,
            'price_per_kg' => null,
        ];
    }

    public function calculateTotal()
    {
        return collect($this->scannedFruits)->sum(function ($fruit) {
            return ($fruit['weight'] ?? 0) * ($fruit['price_per_kg'] ?? 0);
        });
    }

    public function confirmTransaction()
    {
        // Save to transactions table
        // ...
        $this->dispatchBrowserEvent('notify', ['message' => 'Transaction recorded successfully']);
    }

    public function startScanner()
    {
        $this->dispatch('startScanner');
    }

    #[On('qrScanned')]
    public function handleQrScanned($data)
    {
        $code = $data['code'];
        dd($code);
        $this->addScannedFruit($code);
    }

    public function render()
    {
        return view('livewire.module.sales-and-transactions.create-transaction-livewire');
    }
}
