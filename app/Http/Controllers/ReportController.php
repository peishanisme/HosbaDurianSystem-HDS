<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\ReportService;
use App\Reports\Exporters\PdfExporter;

use App\Reports\Exporters\CsvExporter;
use InvalidArgumentException;

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
            'csv'   => CsvExporter::class,
            default => throw new InvalidArgumentException(
                "Unsupported report format: {$format}"
            ),
        };
    }
}
