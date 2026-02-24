<?php

namespace App\Jobs;

use Carbon\Carbon;
use App\Models\Transaction;
use App\Services\BlockchainService;
use Illuminate\Support\Facades\Log;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class SyncTransactionToBlockchainJob implements ShouldQueue
{
    use Dispatchable, Queueable, InteractsWithQueue, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct(public string $transactionUuid)
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(BlockchainService $blockchainService): void
    {
        $transaction = Transaction::where('uuid', $this->transactionUuid)->firstOrFail();
        try {
            if ($transaction->blockchain_tx_hash) {
                // Already synced
                dd('Transaction already synced to blockchain.');
                return;
            }
            // --------------------
            // 3. Generate blockchain hash
            // --------------------
            $transactionHash = hash(
                'sha256',
                $transaction->reference_id .
                    $transaction->buyer->reference_id .
                    number_format($transaction->total_price, 2, '.', '') .
                    $transaction->date
            );

            $transactionHash = '0x' . $transactionHash;
            Log::info('Generated Transaction Hash: ' . $transactionHash);

            // --------------------
            // 4. Push hash to blockchain
            // --------------------
            $response = $blockchainService->createSale(
                $transaction->reference_id,
                $transactionHash
            );

            // --------------------
            // 5. Update transaction status
            // --------------------
            if ($response['success']) {
                $transaction->update([
                    'blockchain_tx_hash' => $response['txHash'],
                    'blockchain_status'  => 'confirmed',
                    'synced_at'          => Carbon::now(),
                ]);
                Log::info('Transaction synced to blockchain: ' . $response['txHash']);
            }
        } catch (\Exception $e) {
            throw $e;
        }
    }
}
