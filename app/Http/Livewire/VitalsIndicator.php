<?php

namespace App\Http\Livewire;

use App\Models\Vital;
use Illuminate\Database\Eloquent\Builder;
use Rappasoft\LaravelLivewireTables\Views\Column;

class VitalsIndicator extends LivewireTableComponent
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
    }

    public function columns(): array
    {
        return [
            Column::make('Id', 'id')
                ->hideIf(1),
            
            Column::make(__('messages.ipd_patient.bp'), 'bp')
                ->sortable()
                ->searchable(),
            Column::make(__('messages.ipd_patient_diagnosis.pulse'), 'pulse')
                ->sortable()
                ->searchable(),
            Column::make(__('messages.ipd_patient_diagnosis.respiration'), 'respiration')
                ->sortable()
                ->searchable(),
        ];
    }

    public function builder(): Builder
    {
        $query = Vital::whereHas('patient.patientUser')->with('patient.patientUser')->select('vitals.*');

        if ($this->patientId != null) {
            $query->where('patient_id', $this->patientId);
        }

        if ($this->opdId != null) {
            $query->where('opd_id', $this->opdId);
        }
        if ($this->ipdId != null) {
            $query->where('ipd_id', $this->ipdId);
        }

        return $query;
    }
}
