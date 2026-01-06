<?php

namespace App\Livewire\Module\SalesAndTransactions;

use App\Models\Fruit;
use App\Models\Transaction;
use App\Traits\SweetAlert;
use Livewire\Component;
use App\Services\BlockchainService;

class TransactionDetailsModalLivewire extends Component
{
    use SweetAlert;

    public string $modalID = 'transactionDetailsModalLivewire', $modalTitle = 'Transaction Details';
    public ?Transaction $transaction = null;
    public array $summary = [];
    public $fruits;

    public bool $blockchainVerified = false; 
    public $blockchainStatus = null;         

    protected $listeners = ['view-transaction' => 'loadTransaction'];

    public function loadTransaction(Transaction $transaction)
    {
        $this->transaction = $transaction;
        $this->summary = $transaction->getFruitSummary();

        // ---------------------------
        // 1. Perform blockchain verification
        // ---------------------------
        if ($transaction->blockchain_tx_hash && $transaction->blockchain_status === 'confirmed') {
            $this->blockchainStatus = 'confirmed';

            $blockchain = app(BlockchainService::class);

            $hashToVerify = hash(
                'sha256',
                $transaction->reference_id .
                    $transaction->buyer->reference_id .
                    $transaction->total_price .
                    $transaction->date
            );
            $hashToVerify = '0x' . $hashToVerify;
            // dd($hashToVerify);

            try {
                $verifyResult = $blockchain->verifySale(
                    $transaction->reference_id,
                    $hashToVerify
                );

                $this->blockchainVerified = $verifyResult['valid'] ?? false;
                $this->blockchainStatus = $verifyResult['status'] == 1 ? 'confirmed' : 'canceled';
            } catch (\Exception $e) {
                $this->blockchainVerified = false;
                $this->blockchainStatus = 'error';
            }
        } else {
            $this->blockchainVerified = false;
            $this->blockchainStatus = 'pending';
        }
    }

    public function resetInput()
    {
        $this->transaction = null;
        $this->fruits = collect();
        $this->blockchainVerified = false;
        $this->blockchainStatus = null;
    }

    public function printReceipt()
    {
        if (!$this->transaction) {
            $this->toastError('No transaction loaded.');
            return;
        }

        $this->dispatch('close-modal', modalID: $this->modalID);

        return redirect()->route('receipt.print', [
            'transaction' => $this->transaction->uuid,
        ]);
    }

    public function render()
    {
        return view('livewire.module.sales-and-transactions.transaction-details-modal-livewire', [
            'blockchainVerified' => $this->blockchainVerified,
            'blockchainStatus'   => $this->blockchainStatus,
        ]);
    }
}
