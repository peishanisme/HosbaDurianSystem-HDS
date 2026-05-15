<?php

namespace App\Livewire\Module\SalesAndTransactions;

use App\Livewire\Forms\TransactionForm;
use App\Models\Transaction;
use App\Traits\SweetAlert;
use Livewire\Attributes\On;
use Livewire\Component;

class TransactionModalLivewire extends Component
{
    use SweetAlert;
    public TransactionForm $form;
    public string $modalID = 'transactionModalLivewire', $modalTitle = 'Transaction Details';
    public ?bool $recordByGrade = null;

    #[On('reset-transaction')]
    public function resetInput()
    {
        $this->form->resetValidation();
        $this->form->reset();
    }

    #[On('edit-transaction')]
    public function edit(Transaction $transaction)
    {
        $this->form->resetValidation();
        $this->form->edit($transaction);
        $this->recordByGrade = $this->form->recordByGrade;
    }

    public function updatedFormRecordByGrade($value)
    {
        $this->form->recordByGrade = filter_var($value, FILTER_VALIDATE_BOOLEAN);
    }

    public function create()
    {
        $validatedData = $this->form->validate();
        try {
            $this->form->create($validatedData);
            $this->alertSuccess('Transaction created successfully', $this->modalID);
        } catch (\Exception $e) {
            $this->alertError('Transaction creation failed: ' . $e->getMessage());
        }
    }

    public function update()
    {
        $validatedData = $this->form->validate();
        try {
            $this->form->update($validatedData);
            $this->alertSuccess('Transaction updated successfully', $this->modalID);
        } catch (\Exception $e) {
            $this->alertError('Transaction update failed: ' . $e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.module.sales-and-transactions.transaction-modal-livewire');
    }
}
