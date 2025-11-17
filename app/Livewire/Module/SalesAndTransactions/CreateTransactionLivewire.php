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

        // fruits uuid and create an array of scanned fruits
        $fruitUuids = ['c218f235-624e-41fb-8f81-0909d97dd369','9dc3964a-5b54-48c5-ba85-9ef084d03eff','bd860a5c-eb8f-4b21-b9cd-00f6db664a1f','4f5f12f8-78e3-4f79-a100-bca918ca61ec'];
        //initialize scanned fruits with data for the fruits with uuids above
        $this->scannedFruits = [];
        foreach ($fruitUuids as $uuid) {
            $fruit = Fruit::where('uuid', $uuid)->first();
            if ($fruit) {
                $this->scannedFruits[] = [
                    'uuid' => $fruit->uuid,
                    'tag' => $fruit->fruit_tag,
                    'species' => $fruit->tree->species->name,
                    'grade' => $fruit->grade,
                    'weight' => $fruit->weight,
                ];
            }
        }

        // $this->scannedFruits = [
        //     [
        //         'uuid' => '21b265b8-5214-4795-a128-62b7f0cd61fa',
        //         'tag' => 'FR000006',
        //         'species' => 'Musang King',
        //         'grade' => 'B',
        //         'weight' => 1.4,
        //     ],

        //     [
        //         'uuid' => '9c91a879-0136-419a-8bb9-1fad14876e51',
        //         'tag' => 'FR000005',
        //         'species' => 'D24',
        //         'grade' => 'A',
        //         'weight' => 1.2,
        //     ],

        //     [
        //         'uuid' => '6e394393-c8d3-4c1f-b2cb-880e93022226',
        //         'tag' => 'FR000010',
        //         'species' => 'Musang King',
        //         'grade' => 'C',
        //         'weight' => 1.5,
        //     ],

        //     [
        //         'uuid' => '076dafb0-6913-417c-8223-91306d0f5896',
        //         'tag' => 'FR000003',
        //         'species' => 'Musang King',
        //         'grade' => 'B',
        //         'weight' => 1.5,
        //     ],

        // ];

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
            $this->form->create($this->form->toArray(),$this->scannedFruits, $this->summary);
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
