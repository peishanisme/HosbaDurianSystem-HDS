<?php
namespace App\Reports\Exporters;

abstract class BaseExporter
{
    protected function buildFilename(array $report, string $ext): string
    {
        $from = $report['from'] ?? null;
        $to   = $report['to'] ?? null;

        $period = ($from && $to)
            ? "{$from}_to_{$to}"
            : now()->format('Ymd_His');

        return str_replace(' ', '_', $report['title'])
            . "_{$period}.{$ext}";
    }
}
