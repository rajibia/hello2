<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithTitle;

abstract class BaseReportExport implements FromCollection, WithHeadings, WithMapping, WithTitle
{
    use Exportable;

    public function headings(): array
    {
        return $this->headings();
    }

    public function map($row): array
    {
        return $this->map($row);
    }
}