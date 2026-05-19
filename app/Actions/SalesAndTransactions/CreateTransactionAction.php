<?php

namespace App\Actions\SalesAndTransactions;

use App\Enum\TransactionGrade;
use App\Models\Transaction;
use Illuminate\Support\Facades\DB;

class CreateTransactionAction
{
    public function handle(array $validatedData): Transaction
    {
        return DB::transaction(function () use ($validatedData) {

            $gradeBreakdown = null;

            $totalAmount = $validatedData['total_amount'] ?? 0;
            $totalWeight = $validatedData['total_weight'] ?? 0;

            if ($validatedData['recordByGrade']) {

                $gradeBreakdown = [];

                foreach (TransactionGrade::values() as $grade) {

                    $gradeBreakdown[$grade] = [
                        'total_weight' =>
                            (float) (
                                $validatedData['grade_breakdown'][$grade]['total_weight']
                                ?? 0
                            ),

                        'total_amount' =>
                            (float) (
                                $validatedData['grade_breakdown'][$grade]['total_amount']
                                ?? 0
                            ),
                    ];
                }

                $totalAmount = collect($gradeBreakdown)
                    ->sum('total_amount');

                $totalWeight = collect($gradeBreakdown)
                    ->sum('total_weight');
            }

            return Transaction::create([
                'date' => $validatedData['date'],
                'total_amount' => $totalAmount,
                'total_weight' => $totalWeight,
                'grade_breakdown' => $gradeBreakdown,
            ]);
        });
    }
}