<?php

namespace App\Livewire\Components;

use Livewire\Component;
use Livewire\Attributes\On;
use Illuminate\Support\Facades\Log;

class TransactionFruitSummaryTable extends Component
{
    public array $scannedFruits = [];
    public array $summary = [];
    public float $discount = 0;

    public function mount()
    {
        $this->groupFruits();
    }

    #[On('refreshTable')]
    public function updateSummary($scannedFruits)
    {
        $this->scannedFruits = $scannedFruits;
        $this->groupFruits();
    }

    public function groupFruits()
    {
        $grouped = [];

        foreach ($this->scannedFruits as $fruit) {
            $key = $fruit['species'] . '-' . $fruit['grade'];

            if (!isset($grouped[$key])) {
                $grouped[$key] = [
                    'species' => $fruit['species'],
                    'grade' => $fruit['grade'],
                    'count' => 0,
                    'total_weight' => 0,
                    'price_per_kg' => $this->summary[$key]['price_per_kg'] ?? 0,
                ];
            }

            $grouped[$key]['count'] += 1;
            $grouped[$key]['total_weight'] += $fruit['weight'];
        }

        uasort($grouped, function ($a, $b) {
            $speciesCompare = strcmp($a['species'], $b['species']);
            if ($speciesCompare === 0) {
                // Grade compare — ensure A before B before C
                return strcmp($a['grade'], $b['grade']);
            }
            return $speciesCompare;
        });

        $this->summary = $grouped;
        $this->emitUpdatedData();
    }

    public function updatedSummary()
    {
        // Recalculate subtotal per item when price changes
        foreach ($this->summary as $key => &$item) {
            $item['subtotal'] = ($item['total_weight'] ?? 0) * ($item['price_per_kg'] ?? 0);
        }

        $this->emitUpdatedData();
    }

    public function updatedDiscount()
    {
        $this->emitUpdatedData();
    }

    protected function emitUpdatedData()
    {
        $subtotal = $this->subtotal;
        $finalAmount = $this->finalAmount;
        $discount = $this->discount;

        // Emit all updated values to parent
        $this->dispatch('updateSummary', summary: $this->summary);
        $this->dispatch('updateTotals', subtotal: $subtotal, finalAmount: $finalAmount, discount: $discount);
    }

    public function getSubtotalProperty()
    {
        return collect($this->summary)->sum('subtotal');
    }

    public function getFinalAmountProperty()
    {
        return max($this->subtotal - $this->discount, 0);
    }

    public function render()
    {
        return view('livewire.components.transaction-fruit-summary-table');
    }
}
