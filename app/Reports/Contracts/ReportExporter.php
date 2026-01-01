<?php

namespace App\Reports\Contracts;

interface ReportExporter
{
    public function export(array $reportData);
}
