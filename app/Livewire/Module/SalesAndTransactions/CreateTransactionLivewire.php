<?php

namespace App\Livewire\Module\SalesAndTransactions;

use Exception;
use App\Models\Buyer;
use App\Models\Fruit;
use Livewire\Component;
use App\Traits\SweetAlert;
use Livewire\Attributes\On;
use Livewire\Attributes\Title;
use Illuminate\Support\Facades\Log;
use App\Livewire\Forms\TransactionForm;

#[Title('Sales & Transactions')]
class CreateTransactionLivewire extends Component
{
    use SweetAlert;
    public array $buyerOptions = [];
    public TransactionForm $form;
    public $scannedFruits = [];
    public $decodedText;
    public $summary = [];
    public ?float $subtotal = 0, $discount = 0, $finalAmount = 0;
    public int $activeStep = 1;

    public function mount()
    {
        $this->buyerOptions = Buyer::all()->mapWithKeys(function ($buyer) {
            return [$buyer->uuid => $buyer->company_name];
        })->toArray();

        $this->dispatch('refreshSummary', scannedFruits: $this->scannedFruits);
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
    public function addScannedFruit(string $uuid)
    {
        $fruit = Fruit::where('uuid', $uuid)->first();

        if (!$fruit) {
            $this->toastError('Fruit not found.');
            return;
        }

        // Avoid duplicates
        if (collect($this->scannedFruits)->pluck('tag')->contains($fruit->fruit_tag)) {
            $this->toastError('Fruit already scanned.');
            return;
        }

        //sold fruit
        if ($fruit->is_sold) {
            $this->toastError('Fruit has already been sold.');
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

    #[On('scan-error')]
    public function scanError($error)
    {
        $this->toastError($error);
    }

    #[On('removeFruit')]
    public function removeScannedFruit($uuid)
    {
        $this->scannedFruits = array_filter($this->scannedFruits, fn($f) => $f['uuid'] !== $uuid);
        $this->dispatch('refreshTable', scannedFruits: $this->scannedFruits);
    }

    #[On('updateTotals')]
    public function updateTotals($subtotal, $finalAmount, $discount)
    {
        $this->form->subtotal = $subtotal;
        $this->form->total_price = $finalAmount;
        $this->form->discount = $discount;
    }

    #[On('updateSummary')]
    public function updateSummary($summary)
    {
        $this->summary = $summary;
        Log::info('Updated summary received', ['summary' => $this->summary]);
    }

    public function nextStep()
    {
        if ($this->activeStep === 1) {
            $this->validate([
                'form.date' => ['required', 'date', 'before_or_equal:today'],
                'form.buyer_uuid' => ['required', 'uuid', 'exists:buyers,uuid'],
            ]);
        }

        if ($this->activeStep === 3) {
            $hasMissingPrice = collect($this->summary)
                ->some(fn($item) => empty($item['price_per_kg']) || $item['price_per_kg'] <= 0);

            if ($hasMissingPrice) {
                $this->addError('summaryPrices', 'Please fill in all prices before proceeding to Step 4.');
                return;
            }
        }

        // Increment step safely
        if ($this->activeStep < 4) {
            $this->activeStep++;

            // STEP 2 → Initialize QR scanner
            if ($this->activeStep === 2) {
                $this->dispatch('init-qr-scanner');
            }
        }
    }

    public function previousStep()
    {
        if ($this->activeStep > 1) {
            $this->activeStep--;

            if ($this->activeStep === 2) {
                $this->dispatch('init-qr-scanner');
            }
        }
    }

    public function create()
    {
        //validate on payment_method and remark
        $this->validate([
            'form.payment_method' => ['required', 'string'],
            'form.remark' => ['nullable', 'string', 'max:500'],
        ]);

        try {
            $this->form->create($this->form->toArray(), $this->scannedFruits, $this->summary);
            $this->alertSuccess('Transaction has been created successfully.');
        } catch (Exception $error) {

            $this->alertError($error->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.module.sales-and-transactions.create-transaction-livewire');
    }
}
