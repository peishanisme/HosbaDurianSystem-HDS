<?php

namespace App\Services;

use App\Reports\Contracts\Reportable;

class ReportService
{
    public function generate(string $modelClass, array $filters)
    {
        if (!is_subclass_of($modelClass, Reportable::class)) {
            throw new \Exception('Model not reportable');
        }

        $from = $filters['from'] ?? null;
        $to   = $filters['to'] ?? null;
        $title = $modelClass::reportTitle();
        $query = $modelClass::reportQuery($filters);
        $data  = $query->get();
        $cols  = $modelClass::reportColumns();

        $totalAmount = null;
        if ($modelClass === \App\Models\Transaction::class) {
            $totalAmount = $data->sum('total_price');
        }

        return compact('title', 'data', 'cols', 'totalAmount', 'modelClass', 'from', 'to');
    }
}
