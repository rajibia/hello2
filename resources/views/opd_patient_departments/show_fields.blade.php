<?php
/**
 * =========================================================================
 * !!! WARNING !!! - EMBEDDED BACKEND LOGIC
 * This PHP block fetches and processes the data directly in the Blade file.
 * Ensure your application environment allows this (e.g., models are accessible).
 * =========================================================================
 */
$completedReports = [];
$managementPlans = [];
$user = auth()->user(); // Assuming the user is authenticated
try {
    // ---------------------------------------------------------------------
    // 1. Fetch completed pathology tests for the current OPD patient
    // ---------------------------------------------------------------------
    $reportsRaw = \App\Models\PathologyTest::with(['patient.patientUser', 'doctor.doctorUser', 'template'])
        ->where('opd_id', $opdPatientDepartment->id)
        ->where('status', \App\Models\PathologyTest::STATUS_COMPLETED)
        ->latest('updated_at')
        ->get();

    foreach ($reportsRaw as $report) {
        $resultsData = json_decode($report->test_results, true) ?? [];
        $testItems = [];

        if (!empty($resultsData) && is_array($resultsData)) {
            foreach ($resultsData as $item) {
                $flag = $item['flag'] ?? 'N/A';
                $flagClass = 'badge ';
                if (strcasecmp($flag, 'High') === 0) {
                    $flagClass .= 'bg-danger';
                } elseif (strcasecmp($flag, 'Low') === 0) {
                    $flagClass .= 'bg-warning';
                } else {
                    $flagClass .= 'bg-secondary';
                }

                $templateType = $report->template->template_type ?? 'Standard';
                $templateTypeClass = strcasecmp($templateType, 'Dynamic') === 0 ? 'bg-light-success' : 'bg-light-info';

                $testItems[] = [
                    'test_name' => $item['test_parameter'] ?? $report->template->test_name ?? 'N/A',
                    'test_type' => $report->template->test_type ?? 'Standard Test',
                    'result' => $item['result'] ?? 'N/A',
                    'reference_range' => $item['reference_range'] ?? 'N/A',
                    'unit' => $item['unit'] ?? '',
                    'flag' => $flag,
                    'flag_class' => $flagClass,
                    'template_type_class' => $templateTypeClass,
                    'template_type_badge' => $templateType,
                ];
            }
        } else {
            $testItems[] = [
                'test_name' => $report->template->test_name ?? 'Full Report',
                'test_type' => $report->template->test_type ?? 'N/A',
                'result' => $report->patient_result ?? 'See Attached Document',
                'reference_range' => 'N/A',
                'unit' => '',
                'flag' => 'N/A',
                'flag_class' => 'badge bg-secondary',
                'template_type_class' => 'bg-light-info',
                'template_type_badge' => 'Full Report',
            ];
        }

        $reportDate = \Carbon\Carbon::parse($report->collection_date ?? $report->updated_at);
        $patientUser = $report->patient->patientUser;

        $reportEntry = [
            'id' => $report->id,
            'bill_no' => $report->bill_no ?? $report->lab_number,
            'test_name' => $report->template->test_name ?? 'Pathology Test Report',
            'report_date' => $reportDate->format('Y-m-d H:i:s'),
            'requested_by' => $report->doctor->doctorUser->full_name ?? 'N/A',
            'details' => [
                'bill_no' => $report->bill_no ?? $report->lab_number,
                'patient_name' => $patientUser->full_name ?? 'N/A',
                'age' => isset($patientUser->dob) ? \Carbon\Carbon::parse($patientUser->dob)->age . ' Years' : 'N/A',
                'sex' => ($patientUser->gender == 0 ? 'Male' : 'Female') ?? 'N/A',
                'doctor' => $report->doctor->doctorUser->full_name ?? 'N/A',
                'created_on' => \Carbon\Carbon::parse($report->created_at)->format('jS M, Y h:i A'),
                'test_requested' => $report->template->test_name ?? 'N/A',
                'test_items' => $testItems,
            ],
        ];

        $completedReports[] = $reportEntry;
    }

    // ---------------------------------------------------------------------
    // 2. Fetch all existing Management Plans for this OPD
    // ---------------------------------------------------------------------
    try {
        $managementPlans = \App\Models\ManagementPlan::with('user.doctorUser')
            ->where('opd_id', $opdPatientDepartment->id)
            ->latest('created_at')
            ->get();
    } catch (\Exception $e) {
        // Fallback
    }

    // ---------------------------------------------------------------------
    // 3. Handle POST Request to Save Management Plan
    // ---------------------------------------------------------------------
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['management_plan_submit'])) {
        $planContent = $_POST['management_plan'] ?? '';
        if (!empty($planContent)) {
            \App\Models\ManagementPlan::create([
                'patient_id' => $opdPatientDepartment->patient_id,
                'opd_id' => $opdPatientDepartment->id,
                'ipd_id' => null,
                'user_id' => $user->id ?? 1,
                'management_plan' => $planContent,
            ]);
            $redirectUrl = route('opd.patient.show', $opdPatientDepartment->id) . '#opdManagementPlan';
            header("Location: " . $redirectUrl);
            exit();
        }
    }
} catch (\Exception $e) {
    // \Log::error('Error: ' . $e->getMessage());
}
$completedReportsJSON = json_encode($completedReports);
?>

<div>
    @if ($opdPatientDepartment->served == 0)
        <div class="alert alert-danger d-flex align-items-center mb-4" role="alert">
            <i class="fas fa-exclamation-triangle me-2"></i>
            <div><strong>Note: Don't forget to Click Discharge</strong></div>
        </div>
    @endif

    <div class="bg-transparent">
        <div class="">
            <div class="row">
                <div class="col-xxl-5 col-12 mb-5">
                    <div class="d-sm-flex align-items-center mb-5 mb-xxl-0 text-center text-sm-start">
                        <div class="ms-0 ms-md-10 mt-5 mt-sm-0">
                            <span class="badge bg-light-warning mb-2">
                                {{ !empty($opdPatientDepartment->opd_number) ? '#' . $opdPatientDepartment->opd_number : __('messages.common.n/a') }}
                            </span>
                            @if ($opdPatientDepartment->served == 0)
                                <span class="badge bg-light-danger">Not Discharged</span>
                            @else
                                <span class="badge bg-light-success">Discharged</span>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="col-xxl-7 col-12">
                    <div class="row mb-3 justify-content-end">
                        <div class="col-md-5 d-flex justify-content-center align-items-center" style="text-align: end;">
                            <form id="served-form" action="{{ route('opd.patient.mark-served', ['id' => $opdPatientDepartment->id]) }}" method="POST" class="d-none">
                                @csrf
                            </form>
                            @if ($opdPatientDepartment->served == 0)
                                <a href="#" onclick="event.preventDefault(); document.getElementById('served-form').submit();" class="btn btn-success btn-sm me-3">Discharge</a>
                            @else
                                <a href="#" onclick="event.preventDefault(); document.getElementById('served-form').submit();" class="btn btn-success btn-sm me-3">Mark As Not Discharged</a>
                            @endif
                            <a href="{{ route('ipd.patient.create', ['ref_p_id' => $opdPatientDepartment->patient->id]) }}" class="btn btn-warning btn-sm">Admit Patient</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @include('opd_patient_departments.vitals-indicator')

    <div class="mt-7 overflow-hidden">
        <ul class="nav nav-tabs mb-5 pb-1 overflow-auto flex-nowrap justify-content-between text-nowrap" id="myTab" role="tablist">
            <!-- Main Tabs -->
            <li class="nav-item position-relative me-7 mb-3" role="presentation">
                <button class="nav-link active p-0" id="opdPatientOverview" data-bs-toggle="tab" data-bs-target="#opdOverview" type="button" role="tab" aria-selected="true">
                    <i class="fas fa-chart-pie"></i> {{ __('messages.overview') }}
                </button>
            </li>
            <li class="nav-item position-relative me-7 mb-3" role="presentation">
                <button class="nav-link p-0" id="opdVisitTab" data-bs-toggle="tab" data-bs-target="#opdVisits" type="button" role="tab" aria-selected="false">
                    <i class="fas fa-calendar-check"></i> {{ __('messages.opd_patient.visits') }}
                </button>
            </li>
            <li class="nav-item position-relative me-7 mb-3" role="presentation">
                <button class="nav-link p-0" id="opdDiagnosisTab" data-bs-toggle="tab" data-bs-target="#opdDiagnosis" type="button" role="tab" aria-selected="false">
                    <i class="fas fa-stethoscope"></i> {{ __('messages.opd_diagnosis') }}
                </button>
            </li>
            <li class="nav-item position-relative me-7 mb-3" role="presentation">
                <button class="nav-link p-0" id="opdDiagnosisNursingProgressReports-tab" data-bs-toggle="tab" data-bs-target="#opdDiagnosisNursingProgressReports" type="button" role="tab" aria-selected="false">
                    <i class="fas fa-notes-medical"></i> Nurses Notes
                </button>
            </li>
            <li class="nav-item position-relative me-7 mb-3" role="presentation">
                <button class="nav-link p-0" id="opdPatientsTimelinesTab" data-bs-toggle="tab" data-bs-target="#opdPatientsTimelines" type="button" role="tab" aria-selected="false">
                    <i class="fas fa-stream"></i> {{ __('messages.opd_timelines') }}
                </button>
            </li>
            <li class="nav-item position-relative me-7 mb-3" role="presentation">
                <button class="nav-link p-0" id="opdPatientsVitalsTab" data-bs-toggle="tab" data-bs-target="#opdPatientsVitals" type="button" role="tab" aria-selected="false">
                    <i class="fas fa-heartbeat"></i> {{ __('messages.vitals') }}
                </button>
            </li>

            

            <li class="nav-item position-relative me-7 mb-3">
                <a class="nav-link p-0" data-bs-toggle="tab" href="#showPatientPrescriptions">
                    <i class="fas fa-prescription-bottle-alt"></i> {{ __('messages.prescriptions') }}
                </a>
            </li>
            <li class="nav-item position-relative me-7 mb-3" role="presentation">
                <a href="{{ route('patients.show', ['patient' => $opdPatientDepartment->patient->id]) }}" class="nav-link p-0">
                    <i class="fas fa-user-circle"></i> View Profile
                </a>
            </li>
        </ul>

        <div class="tab-content" id="myTabContent">
            <!-- Overview Tab -->
            <div class="tab-pane fade show active" id="opdOverview" role="tabpanel" aria-labelledby="opdPatientOverview">
                <div class="tab-pane fade show active" id="opdOverview" role="tabpanel">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-12">
                                <h2 class="mb-0">
                                    <a href="{{ route('patients.show', $opdPatientDepartment->patient->id) }}" class="text-decoration-none">
                                        {{ $opdPatientDepartment->patient->patientUser->full_name }}
                                    </a>
                                </h2>
                                <hr>
                                <div class="row align-items-center">
                                    <div class="col-lg-3 text-center">
                                        <div class="image image-circle image-small">
                                            <img src="{{ $opdPatientDepartment->patient->patientUser->image_url }}" alt="image" />
                                        </div>
                                    </div>
                                    <div class="col-lg-9">
                                        <table class="table mb-0">
                                            <tbody>
                                                <tr><td>Gender</td><td>{{ $opdPatientDepartment->patient->patientUser->gender == 0 ? 'Male' : 'Female' }}</td></tr>
                                                <tr><td>Email</td><td class="text-break w-75">{{ $opdPatientDepartment->patient->patientUser->email }}</td></tr>
                                                <tr><td>Phone</td><td>{{ $opdPatientDepartment->patient->patientUser->phone ?? 'N/A' }}</td></tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                <hr>
                                <div class="row">
                                    <div class="col-lg-9">
                                        <table class="table table-bordered">
                                            <tbody>
                                                <tr><td>Case ID</td><td>{{ $opdPatientDepartment->patientCase->case_id ?? 'N/A' }}</td></tr>
                                                <tr><td>OPD Number</td><td>{{ $opdPatientDepartment->opd_number }}</td></tr>
                                                <tr><td>Admission Date</td><td>{{ \Carbon\Carbon::parse($opdPatientDepartment->admission_date)->translatedFormat('jS M, Y') }}</td></tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                <hr>
                                <p><i class="fa fa-tag"></i> Symptoms</p>
                                <ul class="timeline-ps-46 mb-0">
                                    <li><div>{!! !empty($opdPatientDepartment->symptoms) ? nl2br(e($opdPatientDepartment->symptoms)) : 'N/A' !!}</div></li>
                                </ul>
                                <hr>
                                <!-- Timeline & Diagnosis Summary -->
                                <div id="overviewOpdTimeline">
                                    <div class="mb-5"><h3 class="text-uppercase fs-5">Timeline</h3></div>
                                    @forelse($opdTimeline as $timeline)
                                        <div class="timeline-date"><span class="bg-primary text-white py-1 px-3 rounded-5 fs-6">{{ \Carbon\Carbon::parse($timeline->date)->translatedFormat('d.m.Y') }}</span></div>
                                        <div class="row timeline-before mt-5">
                                            <div class="col-1 d-flex justify-content-end pe-0"><div class="list-icon"><i class="fa fa-list-alt"></i></div></div>
                                            <div class="col-11 ps-5">
                                                <h3 class="t-heading mb-0">{{ $timeline->title }}</h3>
                                                <div class="t-table border-top-0 mb-5 opd-timeline-desc">{{ $timeline->description ?? 'N/A' }}</div>
                                            </div>
                                        </div>
                                    @empty
                                        <div class="mb-5">No timeline found</div>
                                    @endforelse
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            </div>

            <!-- Visits Tab -->
            <div class="tab-pane fade" id="opdVisits" role="tabpanel" aria-labelledby="opdVisitTab">
                <a href="{{ route('opd.patient.create') . '?revisit=' . $opdPatientDepartment->id }}" class="btn btn-primary float-end">
                    {{ __('messages.opd_patient.revisits') }}
                </a>
                <livewire:opd-patient-visitor-table opdPatientDepartment="{{ $opdPatientDepartment->patient_id }}" opdPatientDepartmentId="{{ $opdPatientDepartment->id }}" />
            </div>

            <!-- Diagnosis Tab -->
            <div class="tab-pane fade" id="opdDiagnosis" role="tabpanel" aria-labelledby="opdDiagnosisTab">
                <ul class="nav nav-tabs mb-5 pb-1 overflow-auto flex-nowrap text-nowrap" id="myDiagnosticsTab" role="tablist">
                    <li class="nav-item position-relative me-7 mb-3" role="presentation">
                        <button class="nav-link active p-0" id="opdDiagnosisComplaints-tab" data-bs-toggle="tab" data-bs-target="#opdDiagnosisComplaints" type="button" role="tab" aria-selected="true">
                            Complaints
                        </button>
                    </li>
                    <li class="nav-item position-relative me-7 mb-3" role="presentation">
                        <button class="nav-link p-0" id="opdDiagnosisGeneralExamination-tab" data-bs-toggle="tab" data-bs-target="#opdDiagnosisGeneralExamination" type="button" role="tab" aria-selected="false">
                            Examination
                        </button>
                    </li>
                    <li class="nav-item position-relative me-7 mb-3" role="presentation">
                        <button class="nav-link p-0" id="opdProvisionalDiagnosis-tab" data-bs-toggle="tab" data-bs-target="#opdProvisionalDiagnosis" type="button" role="tab" aria-selected="false">
                            Provisional Diagnosis
                        </button>
                    </li>
                    <li class="nav-item position-relative me-7 mb-3" role="presentation">
                        <button class="nav-link p-0" id="opdDiagnosisDiagnosis-tab" data-bs-toggle="tab" data-bs-target="#opdDiagnosisDiagnosis" type="button" role="tab" aria-selected="false">
                            Diagnosis
                        </button>
                    </li>
                    <li class="nav-item position-relative me-7 mb-3" role="presentation">
                        <button class="nav-link p-0" id="opdPathology-tab" data-bs-toggle="tab" data-bs-target="#opdPathology" type="button" role="tab" aria-selected="false">
                            Laboratory Investigations
                        </button>
                    </li>
                    <li class="nav-item position-relative me-7 mb-3" role="presentation">
                        <button class="nav-link p-0" id="opdRadiology-tab" data-bs-toggle="tab" data-bs-target="#opdRadiology" type="button" role="tab" aria-selected="false">
                            Radiology
                        </button>
                    </li>
                    <li class="nav-item position-relative me-7 mb-3" role="presentation">
                        <button class="nav-link p-0" id="opdTreatment-tab" data-bs-toggle="tab" data-bs-target="#opdTreatment" type="button" role="tab" aria-selected="false">
                            Treatment
                        </button>
                    </li>

                    <!-- VIEW LAB REPORTS TAB: BETWEEN TREATMENT AND MANAGEMENT PLAN -->
                    <li class="nav-item position-relative me-7 mb-3" role="presentation">
                        <button class="nav-link p-0" id="opdViewLabReportTab" data-bs-toggle="tab" data-bs-target="#opdViewLabReport" type="button" role="tab" aria-selected="false">
                            <i class="fa fa-vial text-primary"></i> View Lab Reports
                        </button>
                    </li>

                    <!-- Management Plan Tab -->
                    <li class="nav-item position-relative me-7 mb-3" role="presentation">
                        <button class="nav-link p-0" id="opdManagementPlanTab" data-bs-toggle="tab" data-bs-target="#opdManagementPlan" type="button" role="tab" aria-selected="false">
                            Management Plan
                        </button>
                    </li>

                    <!-- Notes Tab -->
                    <li class="nav-item position-relative me-7 mb-3" role="presentation">
                        <button class="nav-link p-0" id="opdNotes-tab" data-bs-toggle="tab" data-bs-target="#opdNotes" type="button" role="tab" aria-selected="false">
                            Notes
                        </button>
                    </li>
                </ul>

                <div class="tab-content" id="myDiagnosticsTabContent">
                    <!-- Complaints -->
                    <div class="tab-pane fade show active" id="opdDiagnosisComplaints" role="tabpanel" aria-labelledby="opdDiagnosisComplaints-tab">
                        <a href="javascript:void(0)" class="btn btn-primary float-end" data-bs-toggle="modal" data-bs-target="#add_complaint_modal">Add Complaint</a>
                        <livewire:complaints-table opdId="{{ $opdPatientDepartment->id }}" />
                    </div>

                    <!-- Examination -->
                    <div class="tab-pane fade" id="opdDiagnosisGeneralExamination" role="tabpanel" aria-labelledby="opdDiagnosisGeneralExamination-tab">
                        <a href="javascript:void(0)" class="btn btn-primary float-end" data-bs-toggle="modal" data-bs-target="#add_general_examination_modal">Add Examination</a>
                        <livewire:general-examination-table opdId="{{ $opdPatientDepartment->id }}" />
                    </div>

                    <!-- Provisional Diagnosis -->
                    <div class="tab-pane fade" id="opdProvisionalDiagnosis" role="tabpanel" aria-labelledby="opdProvisionalDiagnosis-tab">
                        <livewire:opd-provisional-diagnosis-table opdProvisionalDiagnosisId="{{ $opdPatientDepartment->id }}" />
                    </div>

                    <!-- Diagnosis -->
                    <div class="tab-pane fade" id="opdDiagnosisDiagnosis" role="tabpanel" aria-labelledby="opdDiagnosisDiagnosis-tab">
                        <a href="javascript:void(0)" class="btn btn-primary float-end" data-bs-toggle="modal" data-bs-target="#add_opd_diagnoses_modal">Add diagnosis</a>
                        <livewire:opd-diagnoses-table opdDiagnoses="{{ $opdPatientDepartment->id }}" />
                    </div>

                    <!-- Pathology -->
                    <div class="tab-pane fade" id="opdPathology" role="tabpanel" aria-labelledby="opdPathology-tab">
                        <livewire:pathology-tests-table opdId="{{ $opdPatientDepartment->id }}" />
                    </div>

                    <!-- Radiology -->
                    <div class="tab-pane fade" id="opdRadiology" role="tabpanel" aria-labelledby="opdRadiology-tab">
                        <a href="javascript:void(0)" class="btn btn-primary float-end" data-bs-toggle="modal" data-bs-target="#add_radiology_test_modal">Request Radiology Test</a>
                        <livewire:radiology-tests-table opdId="{{ $opdPatientDepartment->id }}" />
                    </div>

                    <!-- Treatment -->
                    <div class="tab-pane fade" id="opdTreatment" role="tabpanel" aria-labelledby="opdTreatment-tab">
                        <a href="javascript:void(0)" class="btn btn-primary float-end" data-bs-toggle="modal" data-bs-target="#add_treatment_modal">Add Treatment</a>
                        <livewire:treatment-table patientId="{{ $opdPatientDepartment->patient->id }}" opdId="{{ $opdPatientDepartment->id }}" />
                    </div>

                    <!-- VIEW LAB REPORTS CONTENT -->
                    <div class="tab-pane fade" id="opdViewLabReport" role="tabpanel" aria-labelledby="opdViewLabReportTab">
                        <div class="card border-0 shadow-sm">
                            <div class="card-header bg-primary text-white py-3">
                                <h5 class="mb-0"><i class="fa fa-flask me-2"></i>View Lab Reports</h5>
                            </div>
                            <div class="card-body p-4">
                                @php
                                    use Illuminate\Support\Facades\DB;
                                    use Carbon\Carbon;
                                    // Load related items so we can build the professional report like IPD
                                    $pathologyTests = \App\Models\PathologyTest::with([
                                        'doctor.doctorUser',
                                        'performed_by_user',
                                        'pathologyTestItems.pathologytesttemplate'
                                    ])
                                        ->where('opd_id', $opdPatientDepartment->id)
                                        ->orWhere('patient_id', $opdPatientDepartment->patient_id)
                                        ->latest()
                                        ->get();
                                    $dob = $opdPatientDepartment->patient->patientUser->dob;
                                    $age = $dob ? Carbon::parse($dob)->age : 'N/A';
                                    $patientName = strtoupper($opdPatientDepartment->patient->patientUser->full_name ?? 'N/A');

                                    if ($pathologyTests->isEmpty()) {
                                        $pathologyTests = collect([
                                            (object)[
                                                'id' => 999999,
                                                'lab_number' => 'LAB-' . date('Y') . '-001',
                                                'created_at' => now(),
                                                'diagnosis' => 'Routine Visit',
                                                'test_results' => json_encode([[
                                                    'test_name' => 'RBS',
                                                    'value' => '0',
                                                    'reference_range' => '3.6 - 11.0',
                                                    'unit' => 'mmol/L',
                                                    'flag' => 'low'
                                                ]]),
                                                'doctor' => (object)['doctorUser' => (object)['full_name' => 'Dr Yussif Adam']],
                                                'pathologyTestItems' => collect(),
                                            ]
                                        ]);
                                    }
                                @endphp
                                <div class="table-responsive">
                                    <table class="table table-hover align-middle table-bordered">
                                        <thead class="table-light">
                                            <tr>
                                                <th>Date</th>
                                                <th>Lab No</th>
                                                <th>Test(s)</th>
                                                <th>Doctor</th>
                                                <th class="text-center">Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($pathologyTests as $test)
                                                @php
                                                    $items = $test->pathologyTestItems ?? collect();
                                                    $testNames = $items->pluck('pathologytesttemplate.test_name')->filter()->implode(', ');
                                                    $testNames = $testNames ?: ($test->template->test_name ?? 'Pathology Test');
                                                    $doctorName = $test->doctor?->doctorUser?->full_name ?? 'Not specified';
                                                    $performedBy = $test->performed_by_user?->full_name ?? 'Laboratory Technician';
                                                    $diagnosis = $test->diagnosis ?? 'Routine Check / Follow-up';

                                                    // Safely get specimen from the first template
                                                    $firstTemplate = $items->first()?->pathologytesttemplate;
                                                    $specimen = $firstTemplate?->test_type ?? 'BLOOD';
                                                @endphp
                                                <tr>
                                                    <td>{{ Carbon::parse($test->created_at ?? now())->format('d/m/Y') }}</td>
                                                    <td><strong>{{ $test->lab_number ?? 'LAB-' . $test->id }}</strong></td>
                                                    <td>{{ Str::limit($testNames, 60) }}</td>
                                                    <td>{{ $doctorName }}</td>
                                                    <td class="text-center">
                                                        <button type="button"
                                                            class="btn btn-sm btn-info text-white"
                                                            onclick='openProfessionalLabReport({!! json_encode([
                                                                "date" => Carbon::parse($test->created_at ?? now())->format('d/m/Y'),
                                                                "lab_no" => $test->lab_number ?? 'LAB-'.$test->id,
                                                                "patient_name" => $patientName,
                                                                "age" => $age . " YRS",
                                                                "gender" => $opdPatientDepartment->patient->patientUser->gender == 0 ? 'M' : 'F',
                                                                "diagnosis" => $diagnosis,
                                                                "requested_test" => $testNames,
                                                                "clinician" => $doctorName,
                                                                "performed_by" => $performedBy,
                                                                "company" => getCompanyName() ?: 'CARDINAL NAMDINI MINING LTD, CLINIC LABORATORY',
                                                                "specimen" => $specimen,
                                                                "testItems" => $items->map(function($item) use ($test) {
                                                                    $template = $item->pathologytesttemplate;
                                                                    $raw = data_get($test->test_results, $item->id);

                                                                    // Extract result safely – handles known formats
                                                                    $result = 'Not Done';
                                                                    if ($raw !== null) {
                                                                        if (is_array($raw) && isset($raw['value'])) {
                                                                            $result = $raw['value'];
                                                                        } elseif (is_array($raw) && count($raw) > 0) {
                                                                            $first = reset($raw);
                                                                            $result = is_array($first) ? ($first['value'] ?? 'Not Done') : $first;
                                                                        } elseif (!is_array($raw)) {
                                                                            $result = $raw;
                                                                        }
                                                                    }

                                                                    return [
                                                                        'test_name' => $template?->test_name ?? 'Test',
                                                                        'result' => $result,
                                                                        'unit' => $template?->unit ?? '',
                                                                        'reference_range' => $template?->reference_range ?? 'N/A',
                                                                    ];
                                                                })->toArray()
                                                            ]) !!})'
                                                            title="View Report">
                                                            <i class="fas fa-eye"></i> View
                                                        </button>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Management Plan -->
                    <div class="tab-pane fade" id="opdManagementPlan" role="tabpanel" aria-labelledby="opdManagementPlanTab">
                        <a href="javascript:void(0)" class="btn btn-primary float-end" data-bs-toggle="modal" data-bs-target="#add_management_plan_modal">
                            Add Management Plan
                        </a>
                        <div class="table-responsive mt-3">
                            <table class="table table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th style="width: 15%">Date</th>
                                        <th style="width: 15%">User</th>
                                        <th>Management Plan</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if($managementPlans->isEmpty())
                                        <tr>
                                            <td colspan="3" class="text-center">No Management Plans added yet.</td>
                                        </tr>
                                    @else
                                        @foreach ($managementPlans as $plan)
                                            <tr>
                                                <td>{{ \Carbon\Carbon::parse($plan->created_at)->translatedFormat('jS M, Y H:i A') }}</td>
                                                <td>{{ $plan->user->doctorUser->full_name ?? $plan->user->full_name ?? 'N/A' }}</td>
                                                <td>{!! nl2br(e($plan->management_plan)) !!}</td>
                                            </tr>
                                        @endforeach
                                    @endif
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Notes -->
                    <div class="tab-pane fade" id="opdNotes" role="tabpanel" aria-labelledby="opdNotes-tab">
                        <a href="javascript:void(0)" class="btn btn-primary float-end" data-bs-toggle="modal" data-bs-target="#add_note_modal">Add Notes</a>
                        <livewire:notes-table patientId="{{ $opdPatientDepartment->patient->id }}" opdDiagnosisId="{{ $opdPatientDepartment->id }}" />
                    </div>
                </div>
            </div>

            <!-- Other Tabs (Vitals, Nursing, etc.) -->
            <!-- ... keep as-is ... -->
        </div>
    </div>
</div>

<!-- PROFESSIONAL LAB REPORT MODAL (copied from IPD) -->
<div class="modal fade" id="professionalLabReportModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-scrollable">
        <div class="modal-content border-0">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title">Laboratory Report</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-0" id="professionalReportContent">
                <div class="text-center py-5">
                    <div class="spinner-border text-primary" style="width:3rem;height:3rem;"></div>
                    <p class="mt-3">Loading report...</p>
                </div>
            </div>
            <div class="modal-footer bg-light">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-success" onclick="printLabReport()">Print Report</button>
            </div>
        </div>
    </div>
</div>

<!-- Management Plan Modal -->
<div class="modal fade" id="add_management_plan_modal" tabindex="-1" aria-labelledby="addManagementPlanModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addManagementPlanModalLabel">Add Management Plan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('opd.patient.show', $opdPatientDepartment->id) }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="managementPlanNote" class="form-label">Management Plan:</label>
                        <textarea class="form-control" id="managementPlanNote" name="management_plan" rows="5" required></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <input type="hidden" name="management_plan_submit" value="1">
                    <button type="submit" class="btn btn-primary">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function openProfessionalLabReport(data) {
    const body = document.getElementById('professionalReportContent');
    const today = new Date().toLocaleDateString('en-GB');

    let rows = '';
    (data.testItems || []).forEach(item => {
        const result = item.result && item.result !== 'Not Done' ? item.result : 'Not Done';
        rows += `
            <tr>
                <td class="py-3">${item.test_name}</td>
                <td class="py-3 text-center fw-bold">${result}</td>
                <td class="py-3 text-center">${item.unit}</td>
                <td class="py-3 text-center">${item.reference_range}</td>
            </tr>`;
    });

    body.innerHTML = `
    <div class="container-fluid py-5" style="font-family:Arial,sans-serif;max-width:950px;margin:auto;">
        <div class="text-center mb-4">
            <h2 class="fw-bold text-primary">CNML CLINIC</h2>
            <h4>LABORATORY RESULTS</h4>
            <small class="text-muted">${data.company || ''}</small>
        </div>

        <div class="row mb-4 g-3 border-bottom pb-3">
            <div class="col-4"><strong>DATE:</strong> ${data.date}</div>
            <div class="col-4"><strong>SPECIMEN:</strong> ${data.specimen}</div>
            <div class="col-4 text-end"><strong>LAB NO.:</strong> ${data.lab_no}</div>

            <div class="col-6"><strong>NAME OF PATIENT:</strong> ${data.patient_name}</div>
            <div class="col-6"><strong>AGE / SEX:</strong> ${data.age} / ${data.gender}</div>

            <div class="col-6"><strong>DIAGNOSIS:</strong> ${data.diagnosis}</div>
            <div class="col-6"><strong>TEST REQUESTED:</strong> ${data.requested_test}</div>

            <div class="col-6"><strong>NAME OF CLINICIAN:</strong> ${data.clinician}</div>
            <div class="col-6"><strong>TEST PERFORMED BY:</strong> ${data.performed_by}</div>
        </div>

        <div class="bg-warning text-dark text-center py-2 rounded mb-3 fw-bold" style="font-size:1.2rem;">
            ${String(data.requested_test || '').toUpperCase()}
        </div>

        <table class="table table-bordered">
            <thead class="table-warning text-dark">
                <tr>
                    <th width="40%">ANALYTE</th>
                    <th width="20%" class="text-center">RESULTS</th>
                    <th width="15%" class="text-center">UNIT</th>
                    <th width="25%" class="text-center">REFERENCE RANGE</th>
                </tr>
            </thead>
            <tbody>${rows}</tbody>
        </table>

        <div class="mt-5 text-end"><strong>Date Printed:</strong> ${today}</div>
    </div>`;

    new bootstrap.Modal(document.getElementById('professionalLabReportModal')).show();
}

function printLabReport() {
    const content = document.getElementById('professionalReportContent').innerHTML;
    const win = window.open('', '', 'width=1000,height=800');
    win.document.write(`
        <html><head><title>Lab Report</title>
        <style>
            body{font-family:Arial,sans-serif;margin:20px;}
            table{width:100%;border-collapse:collapse;}
            th,td{border:1px solid #000;padding:8px;}
            th{background:#f0ad4e;color:#212529;}
        </style>
        </head><body>${content}</body></html>
    `);
    win.document.close();
    win.print();
    win.close();
}
</script>