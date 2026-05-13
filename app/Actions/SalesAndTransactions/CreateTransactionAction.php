<?php

namespace App\Actions\SalesAndTransactions;

use App\Models\Transaction;
use Illuminate\Support\Facades\DB;

class CreateTransactionAction
{
    public function handle(array $validatedData): Transaction
    {
        return DB::transaction(function () use ($validatedData) {
            if ($validatedData['recordByGrade']) {
                $gradeBreakdown = [
                    'AA' => [
                        'total_weight' => $validatedData['grade_breakdown']['AA']['total_weight'] ?? 0,
                        'total_amount' => $validatedData['grade_breakdown']['AA']['total_amount'] ?? 0,
                    ],
                    'A' => [
                        'total_weight' => $validatedData['grade_breakdown']['A']['total_weight'] ?? 0,
                        'total_amount' => $validatedData['grade_breakdown']['A']['total_amount'] ?? 0,
                    ],
                    'B' => [
                        'total_weight' => $validatedData['grade_breakdown']['B']['total_weight'] ?? 0,
                        'total_amount' => $validatedData['grade_breakdown']['B']['total_amount'] ?? 0,
                    ],
                    'C' => [
                        'total_weight' => $validatedData['grade_breakdown']['C']['total_weight'] ?? 0,
                        'total_amount' => $validatedData['grade_breakdown']['C']['total_amount'] ?? 0,
                    ],
                ];
                $totalAmount = array_sum(array_column($gradeBreakdown, 'total_amount'));
                $totalWeight = array_sum(array_column($gradeBreakdown, 'total_weight'));
            } else {
                $gradeBreakdown = null;
            }

            $transaction = Transaction::create([
                'date' => $validatedData['date'],
                'total_amount' => $validatedData['total_amount'] ?? $totalAmount,
                'total_weight' => $validatedData['total_weight'] ?? $totalWeight,
                'grade_breakdown' => $gradeBreakdown,
            ]);

            return $transaction;
        });
    }
}
