<?php

namespace App\Http\Livewire;

use App\Models\Doctor;
use App\Models\IpdPatientDepartment;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Livewire\WithPagination;
use Rappasoft\LaravelLivewireTables\Views\Column;

class IpdPatientTable extends LivewireTableComponent
{
    use WithPagination;

    public $showButtonOnHeader = true;

    public $showFilterOnHeader = true;

    public $paginationIsEnabled = true;

    public $buttonComponent = 'ipd_patient_departments.add-button';

    public $FilterComponent = ['ipd_patient_departments.filter-button', IpdPatientDepartment::FILTER_STATUS_ARR];

    protected $model = IpdPatientDepartment::class;

    protected $listeners = ['refresh' => '$refresh', 'changeFilter', 'resetPage', 'changeDateFilter'];

    public $statusFilter = 0; // Initialize with a default value. 0 would mean 'all' in this case
    
    public $filter = 'current';
    
    protected $startDate = '';
    protected $endDate = '';
    
    public function mount($filter = 'current')
    {
        $this->filter = $filter;
        
        // For current IPD, don't set date filters by default
        $this->startDate = '';
        $this->endDate = '';
    }

    public $antenatalStatus;

    public function resetPage($pageName = 'page')
    {
        $rowsPropertyData = $this->getRows()->toArray();
        $prevPageNum = $rowsPropertyData['current_page'] - 1;
        $prevPageNum = $prevPageNum > 0 ? $prevPageNum : 1;
        $pageNum = count($rowsPropertyData['data']) > 0 ? $rowsPropertyData['current_page'] : $prevPageNum;

        $this->setPage($pageNum, $pageName);
    }

    public function changeFilter($param, $value)
    {
        $this->resetPage($this->getComputedPageName());
        $this->statusFilter = $value;
        $this->setBuilder($this->builder());
    }
    
    public function changeDateFilter($param, $value)
    {
        $this->resetPage($this->getComputedPageName());
        $this->startDate = $value[0];
        $this->endDate = $value[1];
        $this->setBuilder($this->builder());
    }

    public function configure(): void
    {
        $this->setPrimaryKey('id')
            ->setDefaultSort('ipd_patient_departments.created_at', 'desc')
            ->setQueryStringStatus(false);
    }

    public function columns(): array
    {
        $columns = [
            Column::make(__('messages.ipd_patient.ipd_number'), 'ipd_number')
                ->view('ipd_patient_departments.columns.ipd_number')
                ->sortable()
                ->searchable(),
            Column::make(__('messages.ipd_patient.patient_id'), 'patient.patientUser.first_name')
                ->hideIf('patient.patientUser.first_name')
                ->searchable()
                ->sortable(),
            Column::make(__('messages.ipd_patient.patient_id'), 'patient_id')
                ->hideIf('patient.patientUser.email')
                ->searchable()
                ->sortable(),
            Column::make(__('messages.ipd_patient.patient_id'), 'patient.patientUser.first_name')
                ->view('ipd_patient_departments.columns.patient')
                ->sortable(),
            Column::make(__('messages.ipd_patient.doctor_id'), 'bed_id')
                ->hideIf('bed_id')
                ->searchable()
                ->sortable(),
            Column::make(__('messages.ipd_patient.doctor_id'), 'doctor_id')
                ->hideIf('doctor.doctorUser.first_name')
                ->searchable()
                ->sortable(),
            Column::make(__('messages.ipd_patient.doctor_id'), 'doctor.doctorUser.first_name')
                ->view('ipd_patient_departments.columns.doctor')
                ->searchable()
                ->sortable(),
            Column::make(__('messages.ipd_patient.admission_date'), 'admission_date')
                ->view('ipd_patient_departments.columns.admission_date')
                ->searchable()
                ->sortable(),
            Column::make(__('messages.ipd_patient.bed_id'), 'bed.name')
                ->view('ipd_patient_departments.columns.bed')
                ->searchable()
                ->sortable(),
        ];

        // Conditionally add discharge columns
        // if ($this->statusFilter == 2) { 
        //     // dd($this->statusFilter);
            $columns[] = Column::make('Discharge Date', 'discharge_status')
                ->view('ipd_patient_departments.columns.discharge')
                ->hideIf(1)
                ->sortable();

        //     $columns[] = Column::make('Discharge Status', 'discharge_status')
        //         ->view('ipd_patient_departments.columns.discharge_status')
        //         ->sortable();
        // }
        // $columns[] = Column::make('Discharge Date', 'discharge_status');
                // ->view('ipd_patient_departments.columns.discharge')
                // ->sortable();
        // Use simple column definition with view
        $columns[] = Column::make('Discharged', 'discharge')
            ->view('ipd_patient_departments.columns.discharge');

            // Add the rest of the columns
        $columns[] = Column::make(__('messages.ipd_patient.bill_status'), 'bill_status')
            ->view('ipd_patient_departments.columns.bill_status');

        $columns[] = Column::make(__('messages.common.action'), 'id')
            ->view('ipd_patient_departments.action');

        return $columns;
    }

    public function builder(): Builder
    {
        $query = IpdPatientDepartment::whereHas('patient.patientUser')->whereHas('doctor.doctorUser')
            ->with(['patient.patientUser', 'doctor.doctorUser', 'bed', 'bill'])
            ->select(['ipd_patient_departments.*']); // Explicitly select all fields

        // Apply filter based on URL parameter
        if ($this->filter === 'current') {
            // Current IPD: Show only patients that are not discharged (bill_status = 0)
            $query->where('bill_status', 0);
        } elseif ($this->filter === 'old') {
            // Old IPD: Show only patients that are discharged (bill_status = 1)
            $query->where('bill_status', 1);
        }
        
        // Apply status filter from dropdown
        $query->when(isset($this->statusFilter), function (Builder $q) {
            if ($this->statusFilter == 1) { // Admitted patients
                $q->where('bill_status', 0);
            }
            if ($this->statusFilter == 2) { // Discharged patients
                $q->where('bill_status', 1);
            }
        });
        
        // Apply date range filter if set
        if (!empty($this->startDate) && !empty($this->endDate)) {
            $query->whereBetween('admission_date', [$this->startDate, $this->endDate]);
        }

        return $query;
    }
}