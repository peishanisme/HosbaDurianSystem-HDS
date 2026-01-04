<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Illuminate\Http\Request;
use InvalidArgumentException;
use App\Services\ReportService;
use App\Reports\Exporters\PdfExporter;
use App\Reports\Exporters\ExcelExporter;
use App\Reports\Exporters\ReceiptPdfExporter;

class ReportController extends Controller
{
    protected ReportService $reportService;

    public function __construct(ReportService $reportService)
    {
        $this->reportService = $reportService;
    }

    public function export(Request $request)
    {
        $model  = $request->input('model');
        $format = $request->input('format');

        $report = $this->reportService
            ->generate($model, $request->all());

        $exporter = $this->resolveExporter($format);

        return app($exporter)->export($report);
    }

    protected function resolveExporter(string $format): string
    {
        return match (strtolower($format)) {
            'pdf'   => PdfExporter::class,
            'xlsx'  => ExcelExporter::class,
            default => throw new InvalidArgumentException(
                "Unsupported report format: {$format}"
            ),
        };
    }

    public function printReceipt(Transaction $transaction)
    {
       return app(ReceiptPdfExporter::class)->export($transaction);
    }
}
