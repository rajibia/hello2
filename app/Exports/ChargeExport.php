<?php

namespace App\Exports;

use App\Models\Charge;
use App\Models\ChargeCategory;
use App\Models\ChargeType;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Events\AfterSheet;

class ChargeExport implements FromView, WithTitle, ShouldAutoSize, WithEvents
{
    public function view(): View
    {
        $chargeTypes = ChargeType::where('status', 1)->get()->pluck('name', 'id');

        return view('exports.receptionists.charges',
            ['charges' => Charge::with('chargeCategory')->get(), 'chargeTypes' => $chargeTypes]);
    }

    public function title(): string
    {
        return 'Charges';
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $cellRange = 'A1:W1'; // All headers
                $event->sheet->getDelegate()->getStyle($cellRange)->getFont()->setSize(14);
            },
        ];
    }
}
