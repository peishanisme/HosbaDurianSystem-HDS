<?php

namespace App\Reports\Exporters;

use App\Reports\Contracts\ReportExporter;
use Symfony\Component\HttpFoundation\StreamedResponse;

class CsvExporter implements ReportExporter
{
    public function export(array $reportData)
    {
        $response = new StreamedResponse(function () use ($reportData) {
            $handle = fopen('php://output', 'w');

            // Header
            fputcsv($handle, array_keys($reportData['columns']));

            // Rows
            foreach ($reportData['data'] as $item) {
                fputcsv(
                    $handle,
                    collect($reportData['columns'])
                        ->map(fn ($path) => data_get($item, $path))
                        ->toArray()
                );
            }

            fclose($handle);
        });

        $filename = str_replace(' ', '_', $reportData['title']) . '.csv';

        $response->headers->set('Content-Type', 'text/csv');
        $response->headers->set(
            'Content-Disposition',
            "attachment; filename={$filename}"
        );

        return $response;
    }
}
