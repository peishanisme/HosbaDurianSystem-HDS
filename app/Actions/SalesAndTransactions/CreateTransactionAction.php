<?php

namespace App\Actions\SalesAndTransactions;

use App\Models\Transaction;

class CreateTransactionAction
{
    public function handle(array $validatedData): Transaction
    {
        return Transaction::create($validatedData);
    }
}
