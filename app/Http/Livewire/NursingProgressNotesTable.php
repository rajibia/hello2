<?php

namespace App\Http\Livewire;

use App\Models\NursingProgressNote;
use Illuminate\Database\Eloquent\Builder;
use Rappasoft\LaravelLivewireTables\Views\Column;

class NursingProgressNotesTable extends LivewireTableComponent
{
    protected $model = NursingProgressNote::class;

    // public $showButtonOnHeader = true;

    // public $buttonComponent = 'nursing_progress_notes.add-button';

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
            ->setDefaultSort('nursing_progress_notes.created_at', 'desc')
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
                'nursing_progress_notes.add-button', [
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
                ->view('nursing_progress_notes.columns.date')
                ->searchable()
                ->sortable(),
            Column::make('Notes', 'notes')
                ->sortable()
                ->searchable(),
            Column::make(__('messages.common.action'), 'id')
                ->view('nursing_progress_notes.action'),
        ];
    }

    public function builder(): Builder
    {
        $query = NursingProgressNote::whereHas('patient.patientUser')->with('patient.patientUser')->select('nursing_progress_notes.*');

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
