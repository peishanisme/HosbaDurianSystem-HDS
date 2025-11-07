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
    public $decodedText;

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

    #[On('scan-fruit')]
    public function addScannedFruit()
    {
        $fruit = Fruit::where('uuid', $this->decodedText)->first();

        if (!$fruit) {
            $this->dispatch('notify', message: 'Fruit not found.');
            return;
        }

        // Avoid duplicates
        if (collect($this->scannedFruits)->pluck('tag')->contains($fruit->fruit_tag)) {
            $this->dispatch('notify', message: 'This fruit is already scanned.');
            return;
        }

        $this->scannedFruits[] = [
            'uuid' => $fruit->uuid,
            'tag' => $fruit->fruit_tag,
            'species' => $fruit->tree->species->name,
            'grade' => $fruit->grade,
            'weight' => $fruit->weight,
        ];

        $this->dispatch('refreshTable', scannedFruits: $this->scannedFruits);
    }

    #[On('removeFruit')]
    public function removeScannedFruit($uuid)
    {
        $this->scannedFruits = array_filter($this->scannedFruits, fn($f) => $f['uuid'] !== $uuid);
        $this->dispatch('refreshTable', scannedFruits: $this->scannedFruits);
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


    public function render()
    {
        return view('livewire.module.sales-and-transactions.create-transaction-livewire');
    }
}
