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

        $title = $modelClass::reportTitle();
        $query = $modelClass::reportQuery($filters);
        $data  = $query->get();
        $cols  = $modelClass::reportColumns();

        return compact('title','data', 'cols', 'modelClass');
    }
}
