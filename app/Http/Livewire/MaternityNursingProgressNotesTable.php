<?php

namespace App\Http\Livewire;

use App\Models\NursingProgressNote;
use Illuminate\Database\Eloquent\Builder;
use Rappasoft\LaravelLivewireTables\Views\Column;

class MaternityNursingProgressNotesTable extends LivewireTableComponent
{
    protected $model = NursingProgressNote::class;

    public $showButtonOnHeader = true;
    public $buttonComponent = 'maternity.nursing-add-button';
    public $showFilterOnHeader = false;

    protected $listeners = ['refresh' => '$refresh', 'changeFilter', 'resetPage'];
    public $patientId;
    public $ipdId;

    public function mount($ipdId = null)
    {
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

        $this->setConfigurableAreas([
            'toolbar-right-end' => [
                'maternity.nursing-add-button', [
                    'ipdId' => $this->ipdId,
                ],
            ],
        ]);
    }

    public function columns(): array
    {
        return [
            Column::make('DATE', 'created_at')
                ->view('maternity.nursing.columns.date')
                ->searchable()
                ->sortable(),
            Column::make('NOTES', 'notes')
                ->sortable()
                ->searchable(),
            Column::make('ACTIONS', 'id')
                ->view('maternity.nursing.action'),
        ];
    }

    public function builder(): Builder
    {
        $query = NursingProgressNote::whereHas('patient.patientUser')->with('patient.patientUser')->select('nursing_progress_notes.*');

        if ($this->ipdId) {
            $query->where('patient_id', $this->ipdId);
        }

        return $query;
    }
}
