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

    public function handle(array $validatedData, array $scannedFruits, array $summary): Transaction
    {
        return DB::transaction(function () use ($validatedData, $scannedFruits, $summary) {
            // Step 1: Create the transaction
            $transaction = Transaction::create($validatedData);

            //update transaction uuid in fruits
            foreach ($scannedFruits as $fruitData) {
                $fruit = \App\Models\Fruit::where('uuid', $fruitData['uuid'])->first();
               if ($fruit) {
                    $speciesName = $fruit->tree->species->name ?? null;
                    $grade = $fruit->grade ?? null;

                    if ($speciesName && $grade) {
                        $key = $speciesName . '-' . $grade;
                        $pricePerKg = $summary[$key]['price_per_kg'] ?? 0;

                        $fruit->update([
                            'transaction_uuid' => $transaction->uuid,
                            'price_per_kg' => $pricePerKg,
                        ]);
                    }
                }
            }

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
