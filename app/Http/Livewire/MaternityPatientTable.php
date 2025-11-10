<?php

namespace App\Http\Livewire;

use App\Models\Maternity;
use App\Models\Patient;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Rappasoft\LaravelLivewireTables\Views\Column;

class MaternityPatientTable extends LivewireTableComponent
{
    protected $model = Patient::class;

    public $showButtonOnHeader = true;

    public $buttonComponent = 'maternity.add-button';

    public $showFilterOnHeader = false;

    protected $startDate = '';

    protected $endDate = '';

    public $filter = null;

    protected $listeners = ['refresh' => '$refresh', 'resetPage', 'changeDateFilter', 'showCountModal'];

    // New properties for the modal
    public $showCountModal = false;
    public $countDate = '';
    public $patientCount = 0;

    public function mount($filter = null)
    {
        $this->filter = $filter;

        // Set appropriate date ranges based on filter
        if ($filter === 'upcoming') {
            // For upcoming Maternity (not served), we don't need date filters
            $this->startDate = '';
            $this->endDate = '';
        } elseif ($filter === 'old') {
            // For old Maternity, show older served patients
            $this->startDate = Carbon::now()->subMonths(3)->toDateString(); // Show last 3 months by default
            $this->endDate = Carbon::yesterday()->toDateString();
        } else {
            // Default to today's filter - but don't set date range
            // This ensures we use the date logic in builder() for Maternity Today
            $this->startDate = '';
            $this->endDate = '';
        }

        $this->countDate = Carbon::today()->toDateString(); // Initialize count date
    }

    public function resetPage($pageName = 'page')
    {
        $rowsPropertyData = $this->getRows()->toArray();
        $prevPageNum = $rowsPropertyData['current_page'] - 1;
        $prevPageNum = $prevPageNum > 0 ? $prevPageNum : 1;
        $pageNum = count($rowsPropertyData['data']) > 0 ? $rowsPropertyData['current_page'] : $prevPageNum;

        $this->setPage($pageNum, $pageName);
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
            ->setDefaultSort('maternity.created_at', 'desc')
            ->setQueryStringStatus(false);
        $this->setThAttributes(function (Column $column) {
            if ($column->isField('standard_charge')) {
                return [
                    'class' => 'd-flex justify-content-end',
                    'style' => 'padding-right: 3rem !important',
                ];
            }

            return [];
        });
    }

    public function columns(): array
    {
        return [
            Column::make(__('messages.maternity_patient.maternity_number'), 'maternity_number')
                ->view('maternity.columns.maternity_no')
                ->sortable()
                ->searchable(),
            Column::make(__('messages.ipd_patient.patient_id'), 'patient.patientUser.first_name')
                ->view('maternity.columns.patient')
                ->searchable()
                ->sortable(),
            Column::make(__('messages.ipd_patient.doctor_id'), 'doctor.doctorUser.first_name')
                ->view('maternity.columns.doctor')
                ->sortable(),
            Column::make(__('messages.maternity_patient.appointment_date'), 'appointment_date')
                ->view('maternity.columns.appointment_date')
                ->searchable()
                ->sortable(),
            Column::make(__('messages.doctor_opd_charge.standard_charge'), 'standard_charge')
                ->view('maternity.columns.standard_charge')
                ->searchable()
                ->sortable(),
            Column::make('Served Status', 'served')
                ->view('maternity.columns.served')
                ->sortable()
                ->searchable(),
            Column::make(__('messages.maternity_patient.total_visits'), 'id')
                ->view('maternity.columns.total_visits'),
            Column::make(__('messages.common.action'), 'id')
                ->view('maternity.action'),
        ];
    }

    public function builder(): Builder
    {
        $query = Maternity::whereHas('patient')->whereHas('doctor')
            ->with(['patient.patientUser', 'doctor.doctorUser', 'patient.maternity'])->select('maternity.*');

        // Get today's date with application timezone
        $today = Carbon::today();
        $startOfDay = $today->copy()->startOfDay();
        $endOfDay = $today->copy()->endOfDay();

        // Apply filter based on served status
        if ($this->filter === 'upcoming') {
            $query->where('served', 0); // Not served yet (upcoming)
        } elseif ($this->filter === 'old') {
            // Old Maternity: Get served patients with appointment dates before today
            $query->where('served', 1); // Served

            // Get all records with appointment_date before today's date
            $startOfDay = $today->copy()->startOfDay();
            $query->where('appointment_date', '<', $startOfDay);
        } elseif ($this->filter === 'today') {
            // Today's Maternity: Get served patients with appointment dates today
            $query->where('served', 1); // Served

            // Get all records with appointment_date equal to today's date
            $startOfDay = $today->copy()->startOfDay();
            $endOfDay = $today->copy()->endOfDay();
            $query->whereBetween('appointment_date', [$startOfDay, $endOfDay]);
        } else {
            // Default: Show all maternity records (no filter)
            // Don't apply any served or date filters
        }

        // Apply date range filter if set and not in upcoming Maternity
        // Also don't apply date range filter for default view (Maternity Today) to ensure we see today's records
        if (!empty($this->startDate) && !empty($this->endDate) && $this->filter !== 'upcoming' && $this->filter !== null) {
            $query->whereBetween('appointment_date', [$this->startDate, $this->endDate]);
        }

        return $query;
    }

    // New methods for the modal
    public function openCountModal()
    {
        $this->showCountModal = true;
        $this->getPatientCount(); // Get initial count for today's date
    }

    public function closeCountModal()
    {
        $this->showCountModal = false;
        $this->patientCount = 0;
        $this->countDate = Carbon::today()->toDateString();
    }

    public function getPatientCount()
    {
        $this->patientCount = Maternity::whereDate('appointment_date', $this->countDate)->count();
    }
}
