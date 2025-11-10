<?php

namespace App\Http\Livewire;

use App\Models\DiagnosisCategory;
use App\Models\OpdPatientDepartment;
use App\Models\PatientDiagnosisTest;
use App\Models\Diagnosis;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithPagination;

class MonthlyOutpatientMorbidityReport extends Component
{
    use WithPagination;
    
    protected $paginationTheme = 'bootstrap';
    
    // Date filter properties
    public $dateFilter = 'this_month';
    public $startDate;
    public $endDate;
    
    // Listeners for Livewire events
    protected $listeners = ['refresh' => '$refresh', 'printReport'];
    
    // Mount and lifecycle methods
    public function mount()
    {
        // Initialize with current month's date range
        $this->startDate = Carbon::now()->startOfMonth()->format('Y-m-d');
        $this->endDate = Carbon::now()->endOfMonth()->format('Y-m-d');
        
        $this->changeDateFilter('this_month');
    }
    
    // Update methods for date changes
    public function updatedStartDate()
    {
        $this->dateFilter = 'custom';
        $this->resetPage();
    }
    
    public function updatedEndDate()
    {
        $this->dateFilter = 'custom';
        $this->resetPage();
    }
    
    // Method removed as category filter is no longer needed
    
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
            case 'last_month':
                $this->startDate = Carbon::now()->subMonth()->startOfMonth()->format('Y-m-d');
                $this->endDate = Carbon::now()->subMonth()->endOfMonth()->format('Y-m-d');
                break;
            case 'custom':
                // Dates are already set by the date inputs
                break;
        }
        
        $this->resetPage();
    }
    
    // Category filter methods removed as they are no longer needed
    
    // Get diagnosis data grouped by category
    public function getMorbidityData()
    {
        // Optimize the query by removing unnecessary joins and eager loading
        $query = PatientDiagnosisTest::join('diagnosis_categories', 'patient_diagnosis_tests.category_id', '=', 'diagnosis_categories.id')
            ->whereBetween(DB::raw('DATE(patient_diagnosis_tests.created_at)'), [$this->startDate, $this->endDate]);
        
        return $query->select(
                'diagnosis_categories.id as category_id',
                'diagnosis_categories.name as category_name',
                'diagnosis_categories.code as category_code',
                DB::raw('COUNT(patient_diagnosis_tests.id) as diagnosis_count'),
                DB::raw('COUNT(DISTINCT patient_diagnosis_tests.patient_id) as patient_count')
            )
            ->groupBy('diagnosis_categories.id', 'diagnosis_categories.name', 'diagnosis_categories.code')
            ->orderBy('diagnosis_count', 'desc')
            ->paginate(10);
    }
    
    // Get diagnosis details for a specific category
    public function getCategoryDetails($categoryId)
    {
        return PatientDiagnosisTest::with(['patient.patientUser', 'doctor.doctorUser'])
            ->where('category_id', $categoryId)
            ->whereBetween(DB::raw('DATE(created_at)'), [$this->startDate, $this->endDate])
            ->get();
    }
    
    // Format date for display
    public function formatDate($date)
    {
        if (empty($date)) {
            return 'N/A';
        }
        
        return Carbon::parse($date)->format('M d, Y');
    }
    
    // Get age distribution for a category - optimized to use caching
    public function getAgeDistribution($categoryId)
    {
        // Only calculate this when needed and cache the result
        static $cache = [];
        $cacheKey = $categoryId . '_' . $this->startDate . '_' . $this->endDate;
        
        if (isset($cache[$cacheKey])) {
            return $cache[$cacheKey];
        }
        
        $results = PatientDiagnosisTest::join('patients', 'patient_diagnosis_tests.patient_id', '=', 'patients.id')
            ->join('users', 'patients.user_id', '=', 'users.id')
            ->where('patient_diagnosis_tests.category_id', $categoryId)
            ->whereBetween(DB::raw('DATE(patient_diagnosis_tests.created_at)'), [$this->startDate, $this->endDate])
            ->select(
                DB::raw('CASE 
                    WHEN users.age < 5 THEN "Under 5" 
                    WHEN users.age BETWEEN 5 AND 17 THEN "5-17"
                    WHEN users.age BETWEEN 18 AND 40 THEN "18-40"
                    WHEN users.age BETWEEN 41 AND 60 THEN "41-60"
                    ELSE "Over 60" 
                END as age_group'),
                DB::raw('COUNT(*) as count')
            )
            ->groupBy('age_group')
            ->get();
        
        $cache[$cacheKey] = $results;
        return $results;
    }
    
    // Get gender distribution for a category - optimized to use caching
    public function getGenderDistribution($categoryId)
    {
        // Only calculate this when needed and cache the result
        static $cache = [];
        $cacheKey = $categoryId . '_' . $this->startDate . '_' . $this->endDate;
        
        if (isset($cache[$cacheKey])) {
            return $cache[$cacheKey];
        }
        
        $results = PatientDiagnosisTest::join('patients', 'patient_diagnosis_tests.patient_id', '=', 'patients.id')
            ->join('users', 'patients.user_id', '=', 'users.id')
            ->where('patient_diagnosis_tests.category_id', $categoryId)
            ->whereBetween(DB::raw('DATE(patient_diagnosis_tests.created_at)'), [$this->startDate, $this->endDate])
            ->select(
                'users.gender',
                DB::raw('COUNT(*) as count')
            )
            ->groupBy('users.gender')
            ->get();
        
        $cache[$cacheKey] = $results;
        return $results;
    }
    
    // Print method for the report
    public function printReport()
    {
        $this->emit('print-morbidity-report');
    }
    
    public function render()
    {
        $morbidityData = $this->getMorbidityData();
        
        $formattedStartDate = Carbon::parse($this->startDate)->format('M d, Y');
        $formattedEndDate = Carbon::parse($this->endDate)->format('M d, Y');
        
        // Calculate totals
        $totalPatients = $morbidityData->sum('patient_count');
        $totalDiagnoses = $morbidityData->sum('diagnosis_count');
        
        return view('livewire.monthly-outpatient-morbidity-report', [
            'morbidityData' => $morbidityData,
            'formattedStartDate' => $formattedStartDate,
            'formattedEndDate' => $formattedEndDate,
            'totalPatients' => $totalPatients,
            'totalDiagnoses' => $totalDiagnoses
        ]);
    }
}
