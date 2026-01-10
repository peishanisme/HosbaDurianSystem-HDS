<?php

namespace App\Livewire\Module\SalesAndTransactions;

use Exception;
use App\Models\Buyer;
use Livewire\Component;
use App\Traits\SweetAlert;
use Livewire\Attributes\On;
use App\Livewire\Forms\BuyerForm;

class BuyerModalLivewire extends Component
{
    use SweetAlert;
    protected $listeners = ['refreshComponent' => '$refresh'];

    public Buyer $buyer;
    public BuyerForm $form;
    public string $modalID = 'buyerModalLivewire', $modalTitle = 'Buyer Details';

    #[On('reset-buyer')]
    public function resetInput()
    {
        $this->form->resetValidation();
        $this->form->reset();
    }

    #[On('edit-buyer')]
    public function edit(Buyer $buyer): void
    {
        $this->resetInput();
        $this->form->edit($buyer);
    }

    public function create(): void
    {
        $validatedData = $this->form->validate();

        try {

            $this->form->create($validatedData);
            $this->alertSuccess('Buyer has been created successfully.', $this->modalID);
        } catch (Exception $error) {

            $this->alertError($error->getMessage(), $this->modalID);
        }
    }

    public function update(): void
    {
        $validatedData = $this->form->validate();

        try {

            $this->form->update($validatedData);
            $this->alertSuccess('Buyer has been updated successfully.', $this->modalID);
        } catch (Exception $error) {

            $this->alertError($error->getMessage(), $this->modalID);
        }
    }

    public function render()
    {
        return view('livewire.module.sales-and-transactions.buyer-modal-livewire');
    }
}
