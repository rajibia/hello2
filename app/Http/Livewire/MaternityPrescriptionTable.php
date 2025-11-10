<?php

namespace App\Http\Livewire;

use App\Models\Prescription;
use Illuminate\Database\Eloquent\Builder;
use Rappasoft\LaravelLivewireTables\Views\Column;

class MaternityPrescriptionTable extends LivewireTableComponent
{
    public $maternityId;

    protected $model = Prescription::class;

    public $showButtonOnHeader = true;
    public $buttonComponent = 'maternity.prescription-add-button';
    public $showFilterOnHeader = false;

    protected $listeners = ['refresh' => '$refresh', 'changeFilter', 'resetPage'];

    public function resetPage($pageName = 'page')
    {
        $rowsPropertyData = $this->getRows()->toArray();
        $prevPageNum = $rowsPropertyData['current_page'] - 1;
        $prevPageNum = $prevPageNum > 0 ? $prevPageNum : 1;
        $pageNum = count($rowsPropertyData['data']) > 0 ? $rowsPropertyData['current_page'] : $prevPageNum;

        $this->setPage($pageNum, $pageName);
    }

    public function mount(int $maternityId)
    {
        $this->maternityId = $maternityId;
    }

    public function configure(): void
    {
        $this->setPrimaryKey('id')
            ->setDefaultSort('prescriptions.created_at', 'desc')
            ->setQueryStringStatus(false);

        $this->setConfigurableAreas([
            'toolbar-right-end' => [
                'maternity.prescription-add-button', [
                    'maternityId' => $this->maternityId,
                ],
            ],
        ]);
    }

    public function columns(): array
    {
        return [
            Column::make('PATIENT', 'patient.patientUser.first_name')
                ->view('maternity.prescription.columns.patient')
                ->sortable()
                ->searchable(),
            Column::make('DOCTOR', 'doctor.doctorUser.first_name')
                ->view('maternity.prescription.columns.doctor')
                ->sortable()
                ->searchable(),
            Column::make('ADDED AT', 'created_at')
                ->view('maternity.prescription.columns.created_at')
                ->sortable()
                ->searchable(),
            Column::make('CURRENT MEDICATION', 'current_medication')
                ->label(function ($row) {
                    return $row->current_medication ?: 'N/A';
                })
                ->sortable()
                ->searchable(),
            Column::make('STATUS', 'status')
                ->label(function ($row) {
                    $status = $row->status ? 'Active' : 'Inactive';
                    $class = $row->status ? 'bg-light-success' : 'bg-light-danger';
                    return '<span class="badge ' . $class . '">' . $status . '</span>';
                })
                ->html()
                ->sortable(),
            Column::make('ISSUE STATUS', 'id')
                ->label(function ($row) {
                    return '<span class="badge bg-light-warning">UnPaid</span>';
                })
                ->html()
                ->sortable(),
            Column::make('ACTIONS', 'id')
                ->view('maternity.prescription.action'),
        ];
    }

    public function builder(): Builder
    {
        return Prescription::with(['patient.patientUser', 'doctor.doctorUser', 'getMedicine'])
            ->where('patient_id', $this->maternityId)
            ->select('prescriptions.*');
    }
}
