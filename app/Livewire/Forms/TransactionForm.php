<?php

namespace App\Livewire\Forms;

use App\Actions\SalesAndTransactions\CreateTransactionAction;
use Livewire\Attributes\Validate;
use Livewire\Form;

class TransactionForm extends Form
{
    public ?string $date, $buyer_uuid, $payment_method, $remark;
    public ?float $total_price = 0, $discount = 0, $subtotal = 0;

    // protected function rules(): array
    // {
    //     return [
    //         'date' => ['required', 'date', 'before_or_equal:today'],
    //         'total_price' => ['required', 'numeric', 'min:0'],
    //         'buyer_uuid' => ['required', 'exists:buyers,uuid'],
    //     ];
    // }

    public function create(array $validatedData, array $scannedFruits, array $summary): void
    {
        app(CreateTransactionAction::class)->handle($validatedData, $scannedFruits, $summary);
    }
}
