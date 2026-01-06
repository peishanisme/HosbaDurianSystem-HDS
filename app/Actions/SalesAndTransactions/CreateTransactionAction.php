<?php

namespace App\Actions\SalesAndTransactions;

use Carbon\Carbon;
use App\Models\Transaction;
use Illuminate\Support\Facades\DB;
use App\Services\BlockchainService;
use Illuminate\Support\Facades\Log;

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

            // --------------------
            // 1. Create transaction
            // --------------------
            $transaction = Transaction::create($validatedData);

            // --------------------
            // 2. Update fruits
            // --------------------
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
            $response = $this->blockchain->createSale(
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
            }

            return $transaction;
        });
    }
}
