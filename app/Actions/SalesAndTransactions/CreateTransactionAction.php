<?php

namespace App\Actions\SalesAndTransactions;

use App\Enums\BlockchainStatus;
use App\Models\Transaction;
use App\Services\BlockchainService;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class CreateTransactionAction
{
    protected BlockchainService $blockchain;

    public function __construct(BlockchainService $blockchain)
    {
        $this->blockchain = $blockchain;
    }

    public function handle(array $validatedData): Transaction
    {
        return DB::transaction(function () use ($validatedData) {
            // Step 1: Create the transaction
            $transaction = Transaction::create($validatedData);

            // Step 2: Call the blockchain
            $payload = [
                'transactionId' => $transaction->reference_id,
                'buyerId' => $transaction->buyer->reference_id,
                'totalPrice' => $transaction->total_price,
            ];

            $response = $this->blockchain->createSale($payload);

            // Step 3: Update transaction with tx hash
            if ($response['success']) {
                $transaction->update([
                    'blockchain_tx_hash' => $response['txHash'],
                    'blockchain_status' => 'confirmed',
                    'synced_at' => Carbon::now(),
                ]);
            }

            return $transaction;
        });
    }
}
