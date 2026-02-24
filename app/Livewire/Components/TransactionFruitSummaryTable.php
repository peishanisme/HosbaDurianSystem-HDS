<?php

namespace App\Livewire\Components;

use Livewire\Component;
use Livewire\Attributes\On;

class TransactionFruitSummaryTable extends Component
{
    public array $scannedFruits = [];
    public array $summary = [];
    public string $discountInput = '0';
    public float $discount = 0;


    public function mount()
    {
        $this->rebuildSummary();
    }

    

    #[On('refreshTable')]
    public function updateSummary($scannedFruits)
    {
        $this->scannedFruits = $scannedFruits;
        $this->rebuildSummary();
    }

    public function updatedSummary($value, $key)
    {
        // Recalculate subtotal per row
        foreach ($this->summary as $k => &$item) {
            $item['subtotal'] = ($item['total_weight'] ?? 0) * ($item['price_per_kg'] ?? 0);
        }

        // Emit to parent
        $this->emit('updateSummary', $this->summary);
        $this->emit('updateTotals', $this->subtotal, $this->finalAmount, $this->discount);
    }

    /**
     * Build grouped summary from scanned fruits
     */
    protected function rebuildSummary()
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
                    'subtotal' => 0,
                ];
            }

            $grouped[$key]['count']++;
            $grouped[$key]['total_weight'] += $fruit['weight'];
        }

        // Calculate subtotals
        foreach ($grouped as &$item) {
            $item['subtotal'] = $item['total_weight'] * $item['price_per_kg'];
        }

        uasort(
            $grouped,
            fn($a, $b) =>
            [$a['species'], $a['grade']] <=> [$b['species'], $b['grade']]
        );

        $this->summary = $grouped;

        $this->emitTotals();
    }

    /**
     * Called manually from blade when price_per_kg changes
     */
    public function updatePrice($key, $value)
    {
        $this->summary[$key]['price_per_kg'] = max(0, (float) $value);
        $this->summary[$key]['subtotal'] =
            $this->summary[$key]['total_weight'] * $this->summary[$key]['price_per_kg'];

        $this->emitTotals();
    }

    public function updatedDiscountInput($value)
    {
        // Remove anything non-numeric except dot
        $value = preg_replace('/[^0-9.]/', '', $value);

        $this->discount = (float) ($value ?: 0); // always float
        $this->discountInput = $value;

        $this->emitTotals();
    }

    public function updatedDiscount($value)
    {
        $this->discount = max(0, (float) ($value ?: 0));
        $this->emit('updateTotals', $this->subtotal, $this->finalAmount, $this->discount);
    }

    protected function emitTotals()
    {
        $this->dispatch(
            'updateTotals',
            subtotal: $this->subtotal,
            finalAmount: $this->finalAmount,
            discount: $this->discount
        );

        $this->dispatch('updateSummary', summary: $this->summary);
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
