<?php

namespace App\Actions\SalesAndTransactions;

use App\Models\Transaction;
use Illuminate\Support\Facades\DB;

class UpdateTransactionAction
{
    public function handle(Transaction $transaction, array $validatedData): Transaction
    {
        return DB::transaction(function () use ($transaction, $validatedData) {
            if ($validatedData['recordByGrade']) {

                $gradeBreakdown = [];

                foreach (['AA', 'A', 'B', 'C'] as $grade) {

                    $gradeBreakdown[$grade] = [
                        'total_weight' =>
                        $validatedData['grade_breakdown'][$grade]['total_weight'] ?? 0,

                        'total_amount' =>
                        $validatedData['grade_breakdown'][$grade]['total_amount'] ?? 0,
                    ];
                }

                $totalAmount = collect($gradeBreakdown)
                    ->sum('total_amount');

                $totalWeight = collect($gradeBreakdown)
                    ->sum('total_weight');
            } else {

                $gradeBreakdown = null;

                $totalAmount = $validatedData['total_amount'];

                $totalWeight = $validatedData['total_weight'];
            }

            $transaction->update([
                'date' => $validatedData['date'],
                'total_amount' => $validatedData['total_amount'] ?? $totalAmount,
                'total_weight' => $validatedData['total_weight'] ?? $totalWeight,
                'grade_breakdown' => $gradeBreakdown,
            ]);

            return $transaction;
        });
    }
}
