<?php

namespace App\Http\Livewire;

use App\Models\GeneralExamination;
use Illuminate\Database\Eloquent\Builder;
use Rappasoft\LaravelLivewireTables\Views\Column;

class GeneralExaminationTable extends LivewireTableComponent
{
    protected $model = GeneralExamination::class;

    // public $showButtonOnHeader = false;

    // public $buttonComponent = 'general_examinations.add-button';

    // public $showFilterOnHeader = false;

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
            ->setDefaultSort('general_examinations.created_at', 'desc')
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
                'general_examinations.add-button', [
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
            Column::make(__('messages.advanced_payment.date'), 'created_at')
                ->view('general_examinations.columns.date')
                ->searchable()
                ->sortable(),
            Column::make('General Examination', 'general_examination')
                ->sortable()
                ->searchable(),
            Column::make(__('messages.common.action'), 'id')
                ->view('general_examinations.action'),
        ];
    }

    public function builder(): Builder
    {
        $query = GeneralExamination::whereHas('patient.patientUser')->with('patient.patientUser')->select('general_examinations.*');

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
