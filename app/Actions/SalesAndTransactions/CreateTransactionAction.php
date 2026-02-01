<?php

namespace App\Actions\SalesAndTransactions;

use App\Jobs\SyncTransactionToBlockchainJob;
use App\Models\Transaction;
use Illuminate\Support\Facades\DB;
use App\Services\BlockchainService;
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

            return $transaction;
        });
    }
}
