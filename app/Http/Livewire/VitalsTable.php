<?php

namespace App\Http\Livewire;

use App\Models\Vital;
use Illuminate\Database\Eloquent\Builder;
use Rappasoft\LaravelLivewireTables\Views\Column;

class VitalsTable extends LivewireTableComponent
{
    protected $model = Vital::class;

    public $showButtonOnHeader = true;

    public $buttonComponent = 'vitals.add-button';

    public $showFilterOnHeader = false;

    protected $listeners = ['refresh' => '$refresh', 'changeFilter', 'resetPage'];
    public $patientId;
    public $opdId;
    public $ipdId;

    public function mount($patientId = null, $opdId = null, $ipdId = null)
    {
        $this->patientId = $patientId;

        $this->opdId = $opdId;

        $this->ipdId = $ipdId;
    }

    public function resetPage($pageName = 'page')
    {
        $rowsPropertyData = $this->getRows()->toArray();
        $prevPageNum = $rowsPropertyData['current_page'] - 1;
        $prevPageNum = $prevPageNum > 0 ? $prevPageNum : 1;
        $pageNum = count($rowsPropertyData['data']) > 0 ? $rowsPropertyData['current_page'] : $prevPageNum;

        $this->setPage($pageNum, $pageName);
    }

    public function configure(): void
    {
        $this->setPrimaryKey('id')
            ->setDefaultSort('vitals.created_at', 'desc')
            ->setQueryStringStatus(false);
        $this->setTdAttributes(function (Column $column, $row, $columnIndex, $rowIndex) {
            if ($columnIndex == '5') {
                return [
                    'width' => '8%',
                ];
            }

            return [];
        });

        $this->setConfigurableAreas([
            'toolbar-right-end' => [
                'vitals.add-button', [
                    'patientId' => $this->patientId,
                    'ipdId' => $this->ipdId,
                    'opdId' => $this->opdId,
                ],
            ],
        ]);
        // $this->setThAttributes(function (Column $column) {
        //     if ($column->isField('amount')) {
        //         return [
        //             'class' => 'd-flex justify-content-end',
        //             'style' => 'padding-right: 7rem !important',
        //         ];
        //     }

        //     return [];
        // });
    }

    public function columns(): array
    {
        return [
            Column::make('Id', 'id')
                ->hideIf(1),
            // Column::make(__('messages.ipd_patient.ipd_number'), 'ipd_id')
            //     ->view('vitals.columns.ipd_id')
            //     ->sortable()
            //     ->searchable(),
            // Column::make(__('messages.ipd_patient.ipd_number'), 'ipd_id')
            //     ->view('vitals.columns.opd_id')
            //     ->sortable()
            //     ->searchable(),
            // Column::make(__('messages.advanced_payment.patient'), 'patient.patientUser.first_name')
            //     ->view('vitals.columns.patient')
            //     ->searchable()
            //     ->sortable(),
            Column::make(__('messages.advanced_payment.date'), 'created_at')
                ->view('vitals.columns.date')
                ->searchable()
                ->sortable(),
            Column::make(__('messages.ipd_patient.bp'), 'bp')
                ->sortable()
                ->searchable(),
            Column::make(__('messages.ipd_patient_diagnosis.pulse'), 'pulse')
                ->sortable()
                ->searchable(),
            Column::make(__('messages.ipd_patient_diagnosis.respiration'), 'respiration')
                ->sortable()
                ->searchable(),
            Column::make(__('messages.ipd_patient_diagnosis.temperature'), 'temperature')
                ->sortable()
                ->searchable(),
            Column::make(__('messages.ipd_patient_diagnosis.oxygen_saturation'), 'oxygen_saturation')
                ->sortable()
                ->searchable(),
            Column::make(__('messages.ipd_patient.height'), 'height')
                ->sortable()
                ->searchable(),
            Column::make(__('messages.ipd_patient.weight'), 'weight')
                ->sortable()
                ->searchable(),
            // Column::make('BMI')
            //     ->label(fn($row) => $row->bmi ?? 'N/A'),
Column::make('BMI')
    ->label(function ($row) {
        $bmi = $row->bmi;

        if (!$bmi) return 'N/A';

        if ($bmi < 18.5) {
            return "<span class='text-blue-500 font-bold'> (Underweight)</span>";
        } elseif ($bmi < 25) {
            return "<span class='text-green-600 font-bold'> (Normal)</span>";
        } elseif ($bmi < 30) {
            return "<span class='text-yellow-500 font-bold'> (Overweight)</span>";
        } else {
            return "<span class='text-red-600 font-bold'> (Obese)</span>";
        }
    })
    ->html(), // <-- allows HTML formatting

            Column::make(__('messages.common.action'), 'id')
                ->view('vitals.action'),
        ];
    }
    public function getBmiAttribute()
{
    // Assuming height is stored in centimeters
    if ($this->height && $this->weight) {
        $heightInMeters = $this->height / 100;
        return round($this->weight / ($heightInMeters * $heightInMeters), 1);
    }
    return null;
}


    public function builder(): Builder
    {
        $query = Vital::whereHas('patient.patientUser')->with('patient.patientUser')->select('vitals.*');

        // Get patient ID either directly or from OPD/IPD record
        if ($this->patientId != null) {
            // If patient ID is directly provided, use it
            $query->where('patient_id', $this->patientId);
        } else if ($this->opdId != null || $this->ipdId != null) {
            // If OPD or IPD ID is provided, find the patient ID and show all vitals for that patient
            if ($this->opdId != null) {
                // Get patient ID from OPD record
                $patientId = \App\Models\OpdPatientDepartment::where('id', $this->opdId)->value('patient_id');
                if ($patientId) {
                    $query->where('patient_id', $patientId);
                }
            } else if ($this->ipdId != null) {
                // Get patient ID from IPD record
                $patientId = \App\Models\IpdPatientDepartment::where('id', $this->ipdId)->value('patient_id');
                if ($patientId) {
                    $query->where('patient_id', $patientId);
                }
            }
        }
        
        // Original filtering logic (commented out as requested)
        // if ($this->opdId != null) {
        //     $query->where('opd_id', $this->opdId);
        // }
        // if ($this->ipdId != null) {
        //     $query->where('ipd_id', $this->ipdId);
        // }

        return $query;
    }
}
