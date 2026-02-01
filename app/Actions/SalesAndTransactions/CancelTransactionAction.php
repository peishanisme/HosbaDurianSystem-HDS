<?php

namespace App\Actions\SalesAndTransactions;

use App\Models\Transaction;
use App\Services\BlockchainService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CancelTransactionAction
{
    protected BlockchainService $blockchain;

    public function __construct(BlockchainService $blockchain)
    {
        $this->blockchain = $blockchain;
    }

    public function handle(Transaction $transaction): void
    {
        DB::transaction(function () use ($transaction) {

            // --------------------
            // 1. Cancel transaction (DB FIRST)
            // --------------------
            $transaction->update([
                'is_cancelled' => true,
            ]);

            // --------------------
            // 2. Release fruits
            // --------------------
            foreach ($transaction->fruits as $fruit) {
                $fruit->update([
                    'transaction_uuid' => null,
                    'price_per_kg' => null,
                ]);
            }

            // --------------------
            // 3. Append cancel record to blockchain
            // --------------------
            // try {
            //     $this->blockchain->cancelSale(
            //         $transaction->reference_id
            //     );

            // } catch (\Throwable $e) {
            //     // IMPORTANT: DB stays cancelled even if blockchain fails
            //     Log::error('Blockchain cancel failed', [
            //         'transaction_id' => $transaction->id,
            //         'error' => $e->getMessage(),
            //     ]);
            // }
        });
    }
}
