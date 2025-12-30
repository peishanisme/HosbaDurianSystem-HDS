<?php

namespace App\Reports\Contracts;

interface Reportable
{
    /**
     * Return an Eloquent query used for report generation
     */
    public static function reportQuery(array $filters);

    /**
     * Define report columns (header => field mapping)
     */
    public static function reportColumns(): array;

    /**
     * Title shown in the report (PDF / Excel)
     */
    public static function reportTitle(): string;
}
