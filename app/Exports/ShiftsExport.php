<?php

namespace App\Exports;

use App\Models\Shift;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Events\AfterSheet;

class ShiftsExport implements FromView, WithTitle, ShouldAutoSize, WithEvents
{
    public function view(): View
    {
        // Fetch the shifts data you want to export
        return view('exports.shifts', ['shifts' => Shift::all()]);
    }

    public function title(): string
    {
        return 'Shifts';
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                // Apply styles to the header
                $cellRange = 'A1:E1'; // Adjust based on the number of columns
                $event->sheet->getDelegate()->getStyle($cellRange)->getFont()->setSize(14);
                $event->sheet->getDelegate()->getStyle($cellRange)->getFont()->setBold(true);
                $event->sheet->getDelegate()->getStyle($cellRange)->getAlignment()->setHorizontal('center');
            },
        ];
    }
}
