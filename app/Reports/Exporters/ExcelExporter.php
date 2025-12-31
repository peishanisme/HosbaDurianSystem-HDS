<?php
namespace App\Reports\Exporters;

use App\Reports\Contracts\ReportExporter;
use Maatwebsite\Excel\Facades\Excel;
use App\Reports\Exporters\GenericReportExport;

class ExcelExporter implements ReportExporter
{
    public function export(array $reportData)
    {
        return Excel::download(
            new GenericReportExport(
                $reportData['cols'],
                $reportData['data']
            ),
            str_replace(' ', '_', $reportData['title']) . '.xlsx'
        );
    }
}
