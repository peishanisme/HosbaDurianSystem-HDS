<?php

namespace App\Reports\Exporters;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;


class GenericReportExport implements FromCollection, WithHeadings, WithStyles
{
    protected array $columns;
    protected Collection $data;
    protected ?float $totalAmount;

    public function __construct(array $columns, Collection $data)
    {
        $this->columns = $columns;
        $this->data = $data;

        // 👇 calculate once
        $this->totalAmount = $data->sum('total_price');
    }

    public function headings(): array
    {
        return array_keys($this->columns);
    }

    public function collection()
    {
        $rows = $this->data->map(function ($item) {
            return collect($this->columns)
                ->map(fn ($field) => data_get($item, $field))
                ->toArray();
        });

        // 👇 append total row ONLY if applicable
        if ($this->totalAmount > 0) {
            $totalRow = array_fill(0, count($this->columns), '');

            // Put label in second-last column
            $totalRow[count($this->columns) - 2] = 'TOTAL (RM)';
            $totalRow[count($this->columns) - 1] = $this->totalAmount;

            $rows->push($totalRow);
        }

        return $rows;
    }

    public function styles(Worksheet $sheet)
    {
        $lastRow = $sheet->getHighestRow();

        return [
            $lastRow => [
                'font' => ['bold' => true],
            ],
        ];
    }

    public function map($row): array
    {
        // Already handled in collection()
        return [];
    }
}
