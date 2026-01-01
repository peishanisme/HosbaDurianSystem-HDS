<?php
namespace App\Reports\Exporters;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class GenericReportExport implements FromCollection, WithHeadings
{
    public function __construct(
        protected array $columns,
        protected $data
    ) {}

    public function headings(): array
    {
        return array_keys($this->columns);
    }

    public function collection()
    {
        return $this->data->map(function ($row) {
            return collect($this->columns)
                ->map(fn ($field) => data_get($row, $field))
                ->values();
        });
    }
}
