<?php

namespace App\Http\Livewire;

use App\Models\OpdPatientDepartment;
use App\Models\IpdPatientDepartment;
use Carbon\Carbon;
use Livewire\Component;
use Illuminate\Support\Facades\DB;

class DailyCountReport extends Component
{
    public $date;
    public $startDate;
    public $endDate;
    public $dateFilter = 'today';
    public $ageFilter = 'all';
    public $minAge = null;
    public $maxAge = null;
    
    // OPD counts
    public $opdTotal = 0;
    public $opdNew = 0;
    public $opdOld = 0;
    public $opdMale = 0;
    public $opdFemale = 0;
    
    // IPD counts
    public $ipdTotal = 0;
    public $ipdNew = 0;
    public $ipdOld = 0;
    public $ipdMale = 0;
    public $ipdFemale = 0;
    
    protected $listeners = ['refresh' => '$refresh', 'changeDateFilter', 'changeAgeFilter'];
    
    public function mount()
    {
        // Default to today
        $this->date = Carbon::today()->format('Y-m-d');
        $this->startDate = Carbon::today()->format('Y-m-d');
        $this->endDate = Carbon::today()->format('Y-m-d');
        
        $this->loadCounts();
    }
    
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
                $this->startDate = Carbon::today()->startOfWeek()->format('Y-m-d');
                $this->endDate = Carbon::today()->endOfWeek()->format('Y-m-d');
                break;
            case 'this_month':
                $this->startDate = Carbon::today()->startOfMonth()->format('Y-m-d');
                $this->endDate = Carbon::today()->endOfMonth()->format('Y-m-d');
                break;
            case 'custom':
                // Custom date range will be handled by the date picker
                break;
        }
        
        $this->loadCounts();
    }
    
    public function updatedStartDate()
    {
        $this->dateFilter = 'custom';
        $this->loadCounts();
    }
    
    public function updatedEndDate()
    {
        $this->dateFilter = 'custom';
        $this->loadCounts();
    }
    
    public function changeAgeFilter($filter)
    {
        $this->ageFilter = $filter;
        
        switch ($filter) {
            case 'all':
                $this->minAge = null;
                $this->maxAge = null;
                break;
            case 'child':
                $this->minAge = 0;
                $this->maxAge = 12;
                break;
            case 'teen':
                $this->minAge = 13;
                $this->maxAge = 19;
                break;
            case 'adult':
                $this->minAge = 20;
                $this->maxAge = 59;
                break;
            case 'senior':
                $this->minAge = 60;
                $this->maxAge = null;
                break;
            case 'custom':
                // Custom age range will be handled by the age inputs
                break;
        }
        
        $this->loadCounts();
    }
    
    public function updatedMinAge()
    {
        $this->ageFilter = 'custom';
        $this->loadCounts();
    }
    
    public function updatedMaxAge()
    {
        $this->ageFilter = 'custom';
        $this->loadCounts();
    }
    
    public function loadCounts()
    {
        // Get OPD counts with age filtering
        $opdQuery = OpdPatientDepartment::query()
            ->join('patients', 'opd_patient_departments.patient_id', '=', 'patients.id')
            ->join('users', 'patients.user_id', '=', 'users.id')
            ->whereBetween(DB::raw('DATE(opd_patient_departments.appointment_date)'), [$this->startDate, $this->endDate]);
            
        // Apply age filtering if specified (using age_new field)
        if ($this->minAge !== null || $this->maxAge !== null) {
            $opdQuery->whereNotNull('users.age_new')
                     ->where('users.age_new', '>', 0);
            
            if ($this->minAge !== null && $this->maxAge !== null) {
                $opdQuery->whereBetween('users.age_new', [$this->minAge, $this->maxAge]);
            } elseif ($this->minAge !== null) {
                $opdQuery->where('users.age_new', '>=', $this->minAge);
            } elseif ($this->maxAge !== null) {
                $opdQuery->where('users.age_new', '<=', $this->maxAge);
            }
        }
        
        $this->opdTotal = $opdQuery->count();
            
        $this->opdNew = (clone $opdQuery)->where('opd_patient_departments.is_old_patient', 0)->count();
            
        $this->opdOld = (clone $opdQuery)->where('opd_patient_departments.is_old_patient', 1)->count();
        
        // Get OPD gender counts with age filtering
        $opdGenderQuery = (clone $opdQuery)->select('users.gender', DB::raw('count(*) as count'))
            ->groupBy('users.gender');
            
        $opdGenderCounts = $opdGenderQuery->get()->pluck('count', 'gender')->toArray();
        
        $this->opdMale = $opdGenderCounts[0] ?? 0; // Male is 0
        $this->opdFemale = $opdGenderCounts[1] ?? 0; // Female is 1
        
        // Get IPD counts with age filtering
        $ipdQuery = IpdPatientDepartment::query()
            ->join('patients', 'ipd_patient_departments.patient_id', '=', 'patients.id')
            ->join('users', 'patients.user_id', '=', 'users.id')
            ->whereBetween(DB::raw('DATE(ipd_patient_departments.admission_date)'), [$this->startDate, $this->endDate]);
            
        // Apply age filtering to IPD query if specified (using age_new field)
        if ($this->minAge !== null || $this->maxAge !== null) {
            $ipdQuery->whereNotNull('users.age_new')
                     ->where('users.age_new', '>', 0);
            
            if ($this->minAge !== null && $this->maxAge !== null) {
                $ipdQuery->whereBetween('users.age_new', [$this->minAge, $this->maxAge]);
            } elseif ($this->minAge !== null) {
                $ipdQuery->where('users.age_new', '>=', $this->minAge);
            } elseif ($this->maxAge !== null) {
                $ipdQuery->where('users.age_new', '<=', $this->maxAge);
            }
        }
        
        $this->ipdTotal = $ipdQuery->count();
            
        $this->ipdNew = (clone $ipdQuery)->where('ipd_patient_departments.is_old_patient', 0)->count();
            
        $this->ipdOld = (clone $ipdQuery)->where('ipd_patient_departments.is_old_patient', 1)->count();
        
        // Get IPD gender counts with age filtering
        $ipdGenderQuery = (clone $ipdQuery)->select('users.gender', DB::raw('count(*) as count'))
            ->groupBy('users.gender');
            
        $ipdGenderCounts = $ipdGenderQuery->get()->pluck('count', 'gender')->toArray();
        
        $this->ipdMale = $ipdGenderCounts[0] ?? 0; // Male is 0
        $this->ipdFemale = $ipdGenderCounts[1] ?? 0; // Female is 1
    }
    
    public function render()
    {
        return view('livewire.daily-count-report');
    }
}
