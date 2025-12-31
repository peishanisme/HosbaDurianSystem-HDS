<?php

namespace App\Reports\Exporters;

use Barryvdh\DomPDF\Facade\Pdf;
use App\Reports\Contracts\ReportExporter;

class PdfExporter extends BaseExporter implements ReportExporter
{
    public function export(array $reportData)
    {
        $pdf = Pdf::loadView('components.documents.pdf', [
            'title'   => $reportData['title'],
            'columns' => $reportData['cols'],
            'data'    => $reportData['data'],
            'from'    => $reportData['from'],
            'to'      => $reportData['to'],
        ]);

        return $pdf->download(
            $this->buildFilename($reportData, 'pdf')
        );
    }
}
