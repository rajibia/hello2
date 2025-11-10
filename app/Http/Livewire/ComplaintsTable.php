<?php

namespace App\Http\Livewire;

use App\Models\Complaint;
use Illuminate\Database\Eloquent\Builder;
use Rappasoft\LaravelLivewireTables\Views\Column;

class ComplaintsTable extends LivewireTableComponent
{
    protected $model = Complaint::class;

    // public $showButtonOnHeader = true;

    // public $buttonComponent = 'complaints.add-button';

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
            ->setDefaultSort('complaints.created_at', 'desc')
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
                'complaints.add-button', [
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
                ->view('complaints.columns.date')
                ->searchable()
                ->sortable(),
            Column::make('Main Complaint', 'main_complaint')
                ->sortable()
                ->searchable(),
            Column::make('Main Complaint Progression', 'main_complaint_progression')
                ->sortable()
                ->searchable(),
            Column::make('Direct Questioning', 'direct_questioning')
                ->sortable()
                ->searchable(),
            Column::make('Drug History', 'drug_history')
                ->sortable()
                ->searchable(),
            Column::make(__('messages.common.action'), 'id')
                ->view('complaints.action'),
        ];
    }

    public function builder(): Builder
    {
        $query = Complaint::whereHas('patient.patientUser')->with('patient.patientUser')->select('complaints.*');

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
