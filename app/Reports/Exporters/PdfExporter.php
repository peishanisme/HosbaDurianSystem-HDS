<?php

namespace App\Reports\Exporters;

use Barryvdh\DomPDF\Facade\Pdf;
use App\Reports\Contracts\ReportExporter;

class PdfExporter implements ReportExporter
{
    public function export(array $reportData)
    {
        $pdf = Pdf::loadView('components.reports.pdf', [
            'title'   => $reportData['title'],
            'columns' => $reportData['cols'],
            'data'    => $reportData['data'],
        ]);

        return $pdf->download(
            str_replace(' ', '_', $reportData['title']) . '.pdf'
        );
    }
}
