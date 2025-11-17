<?php

namespace App\Livewire\Module\SalesAndTransactions;

use App\Models\Fruit;
use App\Models\Transaction;
use Livewire\Component;

class TransactionDetailsModalLivewire extends Component
{
    public string $modalID = 'transactionDetailsModalLivewire', $modalTitle = 'Transaction Details';
    public ?Transaction $transaction = null;
    public array $summary = [];
    public $fruits;
    protected $listeners = ['view-transaction' => 'loadTransaction'];

    public function loadTransaction(Transaction $transaction)
    {
        $this->transaction = $transaction;
        $this->loadSummary();
    }

    public function loadSummary(){
        $fruits = Fruit::with('tree.species')
            ->where('transaction_uuid', $this->transaction->uuid)
            ->get();

        $grouped = [];

        foreach ($fruits as $fruit) {
            $key = $fruit->tree->species->name . '-' . $fruit->grade;

            if (!isset($grouped[$key])) {
                $grouped[$key] = [
                    'species' => $fruit->tree->species->name,
                    'grade' => $fruit->grade,
                    'count' => 0,
                    'total_weight' => 0,
                    'price_per_kg' => $fruit->price_per_kg ?? 0,
                    'subtotal' => 0,
                ];
            }

            $grouped[$key]['count']++;
            $grouped[$key]['total_weight'] += $fruit->weight;
        }

        // calculate subtotals
        foreach ($grouped as $key => &$item) {
            $item['subtotal'] = $item['total_weight'] * $item['price_per_kg'];
        }

        // sort nicely by species name then grade
        uasort($grouped, function ($a, $b) {
            $speciesCompare = strcmp($a['species'], $b['species']);
            return $speciesCompare === 0 ? strcmp($a['grade'], $b['grade']) : $speciesCompare;
        });

        $this->summary = $grouped;
    }

    public function resetInput()
    {
        $this->transaction = null;
        $this->fruits = collect();
    }

    public function render()
    {
        return view('livewire.module.sales-and-transactions.transaction-details-modal-livewire');
    }
}
