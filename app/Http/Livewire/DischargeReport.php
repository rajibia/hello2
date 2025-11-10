<?php

namespace App\Http\Livewire;

use App\Models\IpdPatientDepartment;
use App\Models\OpdPatientDepartment;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithPagination;

class DischargeReport extends Component
{
    use WithPagination;
    
    protected $paginationTheme = 'bootstrap';
    
    // Date filter properties
    public $startDate;
    public $endDate;
    public $dateFilter = 'today';
    
    // Discharge status filter
    public $dischargeStatus = 'discharged'; // Default to fully discharged
    
    // Tab selection
    public $activeTab = 'opd'; // Default to OPD tab
    
    // Listeners for Livewire events
    protected $listeners = ['refresh' => '$refresh', 'printOpdReport', 'printIpdReport'];
    
    // Mount and lifecycle methods
    public function mount()
    {
        $this->startDate = Carbon::today()->format('Y-m-d');
        $this->endDate = Carbon::today()->format('Y-m-d');
        
        // Initialize with today's date
        $this->changeDateFilter('today');
    }
    
    // Update methods for date changes
    public function updatedStartDate()
    {
        $this->dateFilter = 'custom';
        $this->loadDischarges();
    }
    
    public function updatedEndDate()
    {
        $this->dateFilter = 'custom';
        $this->loadDischarges();
    }
    
    // Change date filter method (Today, Yesterday, etc.)
    public function changeDateFilter($filter)
    {
        $this->dateFilter = $filter;
        
        switch ($filter) {
            case 'today':
                $this->startDate = Carbon::today()->format('Y-m-d');
                $this->endDate = Carbon::today()->format('Y-m-d');
                break;
            case 'yesterday':
                $this->startDate = Carbon::yesterday()->format('Y-m-d');
                $this->endDate = Carbon::yesterday()->format('Y-m-d');
                break;
            case 'this_week':
                $this->startDate = Carbon::now()->startOfWeek()->format('Y-m-d');
                $this->endDate = Carbon::now()->endOfWeek()->format('Y-m-d');
                break;
            case 'this_month':
                $this->startDate = Carbon::now()->startOfMonth()->format('Y-m-d');
                $this->endDate = Carbon::now()->endOfMonth()->format('Y-m-d');
                break;
            case 'custom':
                // Dates are already set by the date inputs
                break;
        }
        
        $this->loadDischarges();
    }
    
    // Change discharge status filter
    public function changeDischargeStatus($status)
    {
        $this->dischargeStatus = $status;
        $this->loadDischarges();
    }
    
    // Change active tab
    public function changeTab($tab)
    {
        $this->activeTab = $tab;
        // Reset pagination when changing tabs
        $this->resetPage($tab === 'opd' ? 'opdPage' : 'ipdPage');
        // Emit an event to refresh the UI
        $this->emit('refresh');
    }
    
    // Load discharge data based on filters
    public function loadDischarges()
    {
        // Reset pagination when filters change
        $this->resetPage();
    }
    
    // Get OPD discharge data
    public function getOpdDischarges()
    {
        return OpdPatientDepartment::query()
            ->join('patients', 'opd_patient_departments.patient_id', '=', 'patients.id')
            ->join('users', 'patients.user_id', '=', 'users.id')
            ->join('users as doctor', 'opd_patient_departments.doctor_id', '=', 'doctor.id')
            ->when($this->dischargeStatus === 'discharged', function ($query) {
                return $query->where('opd_patient_departments.served', 1);
            })
            ->when($this->dischargeStatus === 'partial', function ($query) {
                // For OPD, we don't have a partial discharge concept like IPD
                // Using served=0 as a proxy for "in progress"
                return $query->where('opd_patient_departments.served', 0);
            })
            ->when($this->dischargeStatus === 'all', function ($query) {
                // Include both served and not served
                return $query;
            })
            ->whereBetween(DB::raw('DATE(opd_patient_departments.appointment_date)'), [$this->startDate, $this->endDate])
            ->select(
                'opd_patient_departments.*', 
                'patients.id as patient_id',
                'users.first_name', 
                'users.last_name',
                'users.gender',
                'doctor.first_name as doctor_first_name', 
                'doctor.last_name as doctor_last_name'
            )
            ->orderBy('opd_patient_departments.appointment_date', 'desc')
            ->paginate(10, ['*'], 'opdPage');
    }
    
    // Get IPD discharge data
    public function getIpdDischarges()
    {
        return IpdPatientDepartment::query()
            ->join('patients', 'ipd_patient_departments.patient_id', '=', 'patients.id')
            ->join('users', 'patients.user_id', '=', 'users.id')
            ->join('users as doctor', 'ipd_patient_departments.doctor_id', '=', 'doctor.id')
            ->when($this->dischargeStatus === 'discharged', function ($query) {
                return $query->where('ipd_patient_departments.bill_status', 1);
            })
            ->when($this->dischargeStatus === 'partial', function ($query) {
                return $query->where('ipd_patient_departments.doctor_discharge', 1)
                            ->where('ipd_patient_departments.bill_status', 0);
            })
            ->when($this->dischargeStatus === 'all', function ($query) {
                return $query->where(function($q) {
                    $q->where('ipd_patient_departments.bill_status', 1)
                      ->orWhere('ipd_patient_departments.doctor_discharge', 1);
                });
            })
            ->whereBetween(DB::raw('DATE(ipd_patient_departments.discharge_date)'), [$this->startDate, $this->endDate])
            ->select(
                'ipd_patient_departments.*', 
                'patients.id as patient_id',
                'users.first_name', 
                'users.last_name',
                'users.gender',
                'doctor.first_name as doctor_first_name', 
                'doctor.last_name as doctor_last_name'
            )
            ->orderBy('ipd_patient_departments.discharge_date', 'desc')
            ->paginate(10, ['*'], 'ipdPage');
    }
    
    // Calculate length of stay for IPD patients
    public function calculateLengthOfStay($admissionDate, $dischargeDate)
    {
        if (empty($admissionDate) || empty($dischargeDate)) {
            return 'N/A';
        }
        
        $admission = Carbon::parse($admissionDate);
        $discharge = Carbon::parse($dischargeDate);
        
        $days = $admission->diffInDays($discharge);
        
        return $days . ' ' . ($days == 1 ? 'day' : 'days');
    }
    
    // Format date for display
    public function formatDate($date)
    {
        if (empty($date)) {
            return 'N/A';
        }
        
        return Carbon::parse($date)->format('M d, Y');
    }
    
    // Print methods for OPD and IPD reports
    public function printOpdReport()
    {
        $this->emit('print-opd-report');
    }
    
    public function printIpdReport()
    {
        $this->emit('print-ipd-report');
    }
    
    public function render()
    {
        $opdDischarges = $this->getOpdDischarges();
        $ipdDischarges = $this->getIpdDischarges();
        
        $formattedStartDate = Carbon::parse($this->startDate)->format('M d, Y');
        $formattedEndDate = Carbon::parse($this->endDate)->format('M d, Y');
        
        return view('livewire.discharge-report', [
            'opdDischarges' => $opdDischarges,
            'ipdDischarges' => $ipdDischarges,
            'formattedStartDate' => $formattedStartDate,
            'formattedEndDate' => $formattedEndDate
        ]);
    }
}
