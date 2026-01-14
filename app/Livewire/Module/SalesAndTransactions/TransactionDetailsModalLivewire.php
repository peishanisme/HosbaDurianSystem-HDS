<?php

namespace App\Livewire\Module\SalesAndTransactions;

use App\Models\Fruit;
use Livewire\Component;
use App\Traits\SweetAlert;
use App\Models\Transaction;
use Livewire\Attributes\On;
use App\Services\BlockchainService;
use Illuminate\Support\Facades\Log;

class TransactionDetailsModalLivewire extends Component
{
    use SweetAlert;

    public string $modalID = 'transactionDetailsModalLivewire', $modalTitle;
    public ?Transaction $transaction = null;
    public array $summary = [];
    public $fruits;

    public bool $blockchainVerified = false;
    public $blockchainStatus = null;

    protected $listeners = ['view-transaction' => 'loadTransaction'];
    public function mount()
    {
        $this->modalTitle = __('messages.transaction_details');
    }   

    public function loadTransaction(Transaction $transaction)
    {
        $this->transaction = $transaction;
        $this->summary = $transaction->getFruitSummary();

        Log::info('[Blockchain] Loading transaction', [
            'transaction_id' => $transaction->id,
            'reference_id' => $transaction->reference_id,
            'blockchain_tx_hash' => $transaction->blockchain_tx_hash,
            'blockchain_status' => $transaction->blockchain_status,
            'env' => app()->environment(),
        ]);

        if ($transaction->blockchain_tx_hash && $transaction->blockchain_status === 'confirmed') {
            $this->blockchainStatus = 'confirmed';

            $blockchain = app(BlockchainService::class);

            $rawString =
                $transaction->reference_id .
                $transaction->buyer->reference_id .
                $transaction->total_price .
                $transaction->date;

            $hashToVerify = '0x' . hash('sha256', $rawString);

            Log::info('[Blockchain] Verification payload', [
                'raw_string' => $rawString,
                'hash' => $hashToVerify,
                'buyer_reference' => $transaction->buyer->reference_id,
                'total_price' => $transaction->total_price,
                'date' => $transaction->date,
            ]);

            try {
                $verifyResult = $blockchain->verifySale(
                    $transaction->reference_id,
                    $hashToVerify
                );

                Log::info('[Blockchain] Verification response', [
                    'response' => $verifyResult,
                ]);

                $this->blockchainVerified = $verifyResult['valid'] ?? false;
                $this->blockchainStatus = ($verifyResult['status'] ?? 0) == 1
                    ? 'confirmed'
                    : 'canceled';
            } catch (\Throwable $e) {

                Log::error('[Blockchain] Verification failed', [
                    'message' => $e->getMessage(),
                    'trace' => $e->getTraceAsString(),
                ]);

                $this->blockchainVerified = false;
                $this->blockchainStatus = 'error';
            }
        } else {

            Log::warning('[Blockchain] Transaction not eligible for verification', [
                'tx_hash_exists' => (bool) $transaction->blockchain_tx_hash,
                'blockchain_status' => $transaction->blockchain_status,
            ]);

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

    public function cancelTransaction()
    {
        $this->alertConfirm('Are you sure you want to cancel this transaction?', 'confirm-cancel');
    }

    #[On('confirm-cancel')]
    public function confirmCancel()
    {
        if (!$this->transaction) {
            $this->toastError('No transaction loaded.');
            return;
        }
        try {
            app(\App\Actions\SalesAndTransactions\CancelTransactionAction::class)
                ->handle($this->transaction);

            $this->alertSuccess('Transaction cancelled successfully.', $this->modalID);
        } catch (\Throwable $e) {
            $this->alertError('Failed to cancel transaction: ' . $e->getMessage(), $this->modalID);
        }
    }

    public function render()
    {
        return view('livewire.module.sales-and-transactions.transaction-details-modal-livewire', [
            'blockchainVerified' => $this->blockchainVerified,
            'blockchainStatus'   => $this->blockchainStatus,
        ]);
    }
}
