<?php

namespace App\Livewire\Forms;

use App\Actions\SalesAndTransactions\CreateTransactionAction;
use App\Models\Transaction;
use Livewire\Form;

class TransactionForm extends Form
{
    public ?Transaction $transaction = null;
    public ?bool $recordByGrade = null;
    public ?string $date = null;
    public ?float $total_amount = null;
    public ?float $total_weight = null;
    public ?array $grade_breakdown = [];

    public function rules(): array
    {
        $rules = [
            'date' => ['required', 'date', 'before_or_equal:today'],
            'recordByGrade' => ['required', 'boolean']
        ];

        if ($this->recordByGrade) {

            $rules['grade_breakdown'] = ['required', 'array'];

            $grades = ['AA', 'A', 'B', 'C'];

            foreach ($grades as $grade) {
                $rules["grade_breakdown.$grade.total_weight"] = [
                    'required_with:grade_breakdown.' . $grade . '.total_amount',
                    'numeric',
                    'min:0'
                ];

                $rules["grade_breakdown.$grade.total_amount"] = [
                    'required_with:grade_breakdown.' . $grade . '.total_weight',
                    'numeric',
                    'min:0'
                ];
            }
        } else {

            $rules['total_amount'] = [
                'required',
                'numeric',
                'min:0'
            ];

            $rules['total_weight'] = [
                'required',
                'numeric',
                'min:0'
            ];
        }

        return $rules;
    }

    public function create(array $validatedData): void
    {
        app(CreateTransactionAction::class)->handle($validatedData);
    }
}
