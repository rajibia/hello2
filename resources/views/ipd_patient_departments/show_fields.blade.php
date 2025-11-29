{{-- resources/views/ipd_patient_departments/show.blade.php --}}
@include('ipd_patient_departments.vitals-indicator')

@php
    if (!isset($ipdPatientDepartment) || !$ipdPatientDepartment->exists) {
        echo '<div class="alert alert-danger text-center">IPD Patient record not found or has been deleted.</div>';
        return;
    }
    use Carbon\Carbon;
    use Illuminate\Support\Collection;

    // Fetch all pathology tests (IPD + patient-wide)
    $pathologyTests = \App\Models\PathologyTest::with([
            'doctor.doctorUser',
            'performed_by_user',
            'pathologyTestItems.pathologytesttemplate'
        ])
        ->where('ipd_id', $ipdPatientDepartment->id)
        ->orWhere('patient_id', $ipdPatientDepartment->patient_id)
        ->latest('created_at')
        ->get();

    // Patient details for lab report
    $patientDob = $ipdPatientDepartment->patient->patientUser->dob;
    $patientAge = $patientDob ? Carbon::parse($patientDob)->age : 'N/A';
    $patientGender = $ipdPatientDepartment->patient->patientUser->gender == 0 ? 'M' : 'F';
    $patientName = strtoupper($ipdPatientDepartment->patient->patientUser->full_name ?? 'N/A');

    // Management Plans
    $managementPlans = \App\Models\ManagementPlan::with('user')
        ->where('ipd_id', $ipdPatientDepartment->id)
        ->latest()
        ->get();
@endphp

<div class="mt-7 overflow-hidden">
    <ul class="nav nav-tabs mb-5 pb-1 overflow-auto flex-nowrap justify-content-between text-nowrap" id="myTab" role="tablist">
        <li class="nav-item position-relative me-7 mb-3" role="presentation">
            <button class="nav-link active p-0" id="ipdOverview" data-bs-toggle="tab" data-bs-target="#poverview"
                type="button" role="tab" aria-controls="overview" aria-selected="true">
                <i class="fas fa-chart-pie"></i> {{ __('messages.overview') }}
            </button>
        </li>

        <li class="nav-item position-relative me-7 mb-3" role="presentation">
            <button class="nav-link p-0" id="diagnosis-tab" data-bs-toggle="tab" data-bs-target="#ipdDiagnosis"
                type="button" role="tab">
                <i class="fa fa-stethoscope me-2"></i> {{ __('messages.ipd_diagnosis') }}
            </button>
        </li>

        <li class="nav-item position-relative me-7 mb-3" role="presentation">
            <button class="nav-link p-0" data-bs-toggle="tab" data-bs-target="#ipdDiagnosisNursingProgressReports">
                <i class="fas fa-notes-medical"></i> Nurses Notes
            </button>
        </li>

        <li class="nav-item position-relative me-7 mb-3" role="presentation">
            <button class="nav-link p-0" data-bs-toggle="tab" data-bs-target="#ipdConsultantInstruction">
                <i class="fa fa-info-circle me-2"></i> {{ __('messages.ipd_consultant_register') }}
            </button>
        </li>

        <li class="nav-item position-relative me-7 mb-3" role="presentation">
            <button class="nav-link p-0" data-bs-toggle="tab" data-bs-target="#ipdOperation">
                <i class="fa fa-sitemap me-2"></i> {{ __('messages.operations') }}
            </button>
        </li>

        <li class="nav-item position-relative me-7 mb-3" role="presentation">
            <button class="nav-link p-0" data-bs-toggle="tab" data-bs-target="#ipdCharges">
                <i class="fa fa-money-bill-wave me-2"></i> {{ __('messages.ipd_charges') }}
            </button>
        </li>

        <li class="nav-item position-relative me-7 mb-3" role="presentation">
            <button class="nav-link p-0" data-bs-toggle="tab" data-bs-target="#ipdTimelines">
                <i class="fa fa-clock me-2"></i> {{ __('messages.ipd_timelines') }}
            </button>
        </li>

        <li class="nav-item position-relative me-7 mb-3" role="presentation">
            <button class="nav-link p-0" data-bs-toggle="tab" data-bs-target="#ipdVitals">
                <i class="fa fa-heartbeat me-2"></i> {{ __('messages.vitals') }}
            </button>
        </li>

        <li class="nav-item position-relative me-7 mb-3" role="presentation">
            <button class="nav-link p-0" data-bs-toggle="tab" data-bs-target="#ipdPayment">
                <i class="fa fa-credit-card me-2"></i> {{ __('messages.account.payments') }}
            </button>
        </li>

        <li class="nav-item position-relative me-7 mb-3">
            <a class="nav-link p-0" data-bs-toggle="tab" href="#showPatientPrescriptions">
                <i class="fas fa-prescription-bottle-alt"></i> {{ __('messages.prescriptions') }}
            </a>
        </li>

        <li class="nav-item position-relative me-7 mb-3" role="presentation">
            <button class="nav-link p-0" data-bs-toggle="tab" data-bs-target="#ipdBill">
                <i class="fa fa-file-invoice-dollar me-2"></i> {{ __('messages.bills') }}
            </button>
        </li>
    </ul>

    <div class="tab-content" id="myTabContent">

        <!-- OVERVIEW TAB -->
        <div class="tab-pane fade show active" id="poverview" role="tabpanel">
            <div class="card">
                <div class="card-body p-4">
                    <div class="row">
                        <div class="col-6">
                            <div class="row">
                                <h2 class="mb-0">
                                    <a href="{{ route('patients.show', $ipdPatientDepartment->patient->id) }}"
                                        class="text-decoration-none">
                                        {{ $ipdPatientDepartment->patient->patientUser->full_name }}
                                    </a>
                                </h2>
                            </div>
                            <hr>
                            <div class="row align-items-center">
                                <div class="col-lg-3 text-center">
                                    <div class="image image-circle image-small">
                                        <img src="{{ $ipdPatientDepartment->patient->patientUser->image_url }}" alt="Patient" />
                                    </div>
                                </div>
                                <div class="col-lg-9">
                                    <table class="table mb-0">
                                        <tbody>
                                            <tr><td>{{ __('messages.user.gender') }}</td><td>{{ $ipdPatientDepartment->patient->patientUser->gender == 0 ? 'Male' : 'Female' }}</td></tr>
                                            <tr><td>{{ __('messages.user.email') }}</td><td class="text-break w-75">{{ $ipdPatientDepartment->patient->patientUser->email }}</td></tr>
                                            <tr><td>{{ __('messages.user.phone') }}</td><td>{{ $ipdPatientDepartment->patient->patientUser->phone ?? __('messages.common.n/a') }}</td></tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <hr>
                            <div class="row">
                                <div class="col-lg-9">
                                    <table class="table table-bordered">
                                        <tbody>
                                            <tr><td>{{ __('messages.case.case_id') }}</td><td>{{ $ipdPatientDepartment->patientCase->case_id ?? __('messages.common.n/a') }}</td></tr>
                                            <tr><td>{{ __('messages.ipd_patient.ipd_number') }}</td><td>{{ $ipdPatientDepartment->ipd_number }}</td></tr>
                                            <tr><td>{{ __('messages.ipd_patient.admission_date') }}</td><td>{{ \Carbon\Carbon::parse($ipdPatientDepartment->admission_date)->translatedFormat('jS M, Y') }}</td></tr>
                                            <tr><td>{{ __('messages.ipd_patient.bed_id') }}</td><td>{{ $ipdPatientDepartment->bed->name ?? '' }}</td></tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <!-- Rest of overview content remains unchanged -->
                        </div>
                        <div class="col-6">
                            <!-- Right side overview content (payments, prescriptions, etc.) -->
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- DIAGNOSIS MAIN TAB -->
        <div class="tab-pane fade" id="ipdDiagnosis" role="tabpanel">
            <ul class="nav nav-tabs mb-5 pb-1 overflow-auto flex-nowrap text-nowrap" id="myDiagnosticsTab" role="tablist">
                <li class="nav-item position-relative me-7 mb-3">
                    <button class="nav-link active p-0" data-bs-toggle="tab" data-bs-target="#ipdDiagnosisComplaints">
                        <i class="fa fa-comments"></i> Complaints
                    </button>
                </li>
                <li class="nav-item position-relative me-7 mb-3">
                    <button class="nav-link p-0" data-bs-toggle="tab" data-bs-target="#ipdDiagnosisGeneralExamination">
                        <i class="fa fa-search"></i> Examination
                    </button>
                </li>
                <li class="nav-item position-relative me-7 mb-3">
                    <button class="nav-link p-0" data-bs-toggle="tab" data-bs-target="#ipdProvisionalDiagnosis">
                        <i class="fa fa-stethoscope"></i> Provisional Diagnosis
                    </button>
                </li>
                <li class="nav-item position-relative me-7 mb-3">
                    <button class="nav-link p-0" data-bs-toggle="tab" data-bs-target="#ipdDiagnosisDiagnosis">
                        <i class="fa fa-stethoscope"></i> Diagnosis
                    </button>
                </li>

                @role('Admin|Doctor|Receptionist|Nurse')
                <li class="nav-item position-relative me-7 mb-3">
                    <button class="nav-link p-0" data-bs-toggle="tab" data-bs-target="#ipdPrescriptions">
                        <i class="fa fa-prescription-bottle-alt"></i> Prescription
                    </button>
                </li>
                @endrole

                <li class="nav-item position-relative me-7 mb-3">
                    <button class="nav-link p-0" data-bs-toggle="tab" data-bs-target="#ipdPathology">
                        <i class="fa fa-vial"></i> Laboratory Investigations
                    </button>
                </li>

                <li class="nav-item position-relative me-7 mb-3">
                    <button class="nav-link p-0" data-bs-toggle="tab" data-bs-target="#ipdRadiology">
                        <i class="fa fa-x-ray"></i> Radiology
                    </button>
                </li>

                <!-- VIEW LAB REPORTS - INSIDE DIAGNOSIS, BEFORE TREATMENT & NOTES -->
                <li class="nav-item position-relative me-7 mb-3">
                    <button class="nav-link p-0" data-bs-toggle="tab" data-bs-target="#ipdViewLabReports">
                        <i class="fas fa-eye text-primary"></i> View Lab Reports
                        @if($pathologyTests->count() > 0)
                            <span class="badge bg-danger ms-1">{{ $pathologyTests->count() }}</span>
                        @endif
                    </button>
                </li>

                <!-- Treatment & Notes come after -->
                <li class="nav-item position-relative me-7 mb-3">
                    <button class="nav-link p-0" data-bs-toggle="tab" data-bs-target="#ipdTreatment">
                        <i class="fa fa-ambulance"></i> Treatment
                    </button>
                </li>

                <li class="nav-item position-relative me-7 mb-3">
                    <button class="nav-link p-0" data-bs-toggle="tab" data-bs-target="#ipdNotes">
                        <i class="fa fa-file-alt"></i> Notes
                    </button>
                </li>

                <li class="nav-item position-relative me-7 mb-3">
                    <button class="nav-link p-0" data-bs-toggle="tab" data-bs-target="#ipdManagementPlan">
                        Management Plan
                    </button>
                </li>
            </ul>

            <div class="tab-content" id="myDiagnosticsTabContent">

                <!-- Complaints -->
                <div class="tab-pane fade show active" id="ipdDiagnosisComplaints">
                    <a href="javascript:void(0)" class="btn btn-primary float-end" data-bs-toggle="modal" data-bs-target="#add_complaint_modal">Add Complaint</a>
                    <livewire:complaints-table ipdId="{{ $ipdPatientDepartment->id }}" />
                </div>

                <!-- Examination -->
                <div class="tab-pane fade" id="ipdDiagnosisGeneralExamination">
                    <a href="javascript:void(0)" class="btn btn-primary float-end" data-bs-toggle="modal" data-bs-target="#add_general_examination_modal">Add Examination</a>
                    <livewire:general-examination-table ipdId="{{ $ipdPatientDepartment->id }}" />
                </div>

                <!-- Provisional Diagnosis -->
                <div class="tab-pane fade" id="ipdProvisionalDiagnosis">
                    <livewire:ipd-provisional-diagnosis-table ipdProvisionalDiagnosisId="{{ $ipdPatientDepartment->id }}" />
                </div>

                <!-- Final Diagnosis -->
                <div class="tab-pane fade" id="ipdDiagnosisDiagnosis">
                    <livewire:ipd-diagnosis-table ipdDiagnosisId="{{ $ipdPatientDepartment->id }}" />
                </div>

                @role('Admin|Doctor|Receptionist|Nurse')
                <div class="tab-pane fade" id="ipdPrescriptions">
                    <a href="javascript:void(0)" class="btn btn-primary float-end" data-bs-toggle="modal" data-bs-target="#addIpdPrescriptionModal">
                        {{ __('messages.ipd_patient_prescription.new_prescription') }}
                    </a>
                    <livewire:ipd-prescription-table ipdPrescriptionId="{{ $ipdPatientDepartment->id }}" />
                </div>
                @endrole

                <!-- Pathology -->
                <div class="tab-pane fade" id="ipdPathology">
                    <livewire:pathology-tests-table ipdId="{{ $ipdPatientDepartment->id }}" />
                </div>

                <!-- Radiology -->
                <div class="tab-pane fade" id="ipdRadiology">
                    <livewire:radiology-tests-table ipdId="{{ $ipdPatientDepartment->id }}" />
                </div>

                <!-- VIEW LAB REPORTS TAB (WITH EYE ICON) -->
                <div class="tab-pane fade" id="ipdViewLabReports">
                    <div class="card border-0 shadow-sm">
                        <div class="card-header bg-primary text-white py-3">
                            <h5 class="mb-0"><i class="fas fa-eye me-2"></i> View Lab Reports</h5>
                        </div>
                        <div class="card-body p-4">
                            @if($pathologyTests->isEmpty())
                                <div class="alert alert-info text-center mb-0">
                                    No laboratory reports found for this patient.
                                </div>
                            @else
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
                                                    $items = $test->pathologyTestItems;
                                                    $testNames = $items->pluck('pathologytesttemplate.test_name')->filter()->implode(', ');
                                                    if (!$testNames) $testNames = 'Pathology Test';
                                                    $doctorName = $test->doctor?->doctorUser?->full_name ?? 'Not specified';
                                                    $performedBy = $test->performed_by_user?->full_name ?? 'Laboratory Technician';
                                                    $diagnosis = $test->diagnosis ?? 'Routine Check / Follow-up';
                                                @endphp
                                                <tr>
                                                    <td>{{ $test->created_at->format('d/m/Y') }}</td>
                                                    <td><strong>{{ $test->lab_number ?? 'LAB-'.$test->id }}</strong></td>
                                                    <td>{{ Str::limit($testNames, 60) }}</td>
                                                    <td>{{ $doctorName }}</td>
                                                    <td class="text-center">
                                                        <button type="button"
                                                            class="btn btn-sm btn-info text-white"
                                                            onclick='openProfessionalLabReport({!! json_encode([
                                                                "id" => $test->id,
                                                                "date" => $test->created_at->format('d/m/Y'),
                                                                "lab_no" => $test->lab_number ?? 'LAB-'.$test->id,
                                                                "patient_name" => $patientName,
                                                                "age" => $patientAge,
                                                                "gender" => $patientGender,
                                                                "diagnosis" => $diagnosis,
                                                                "requested_test" => $testNames,
                                                                "clinician" => $doctorName,
                                                                "performed_by" => $performedBy,
                                                                "company" => getCompanyName() ?: 'CARDINAL NAMDINI MINING LTD, CLINIC LABORATORY',
                                                                "testItems" => $test->pathologyTestItems->map(function($item) use ($test) {
                                                                    $template = $item->pathologytesttemplate;
                                                                    $results = $test->test_results[$item->id] ?? [];
                                                                    return [
                                                                        'test_name' => $template->test_name ?? 'Test',
                                                                        'specimen' => $template->test_type ?? 'BLOOD',
                                                                        'results' => $results,
                                                                        'form_config' => $template->form_configuration ?? []
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
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Treatment -->
                <div class="tab-pane fade" id="ipdTreatment">
                    <a href="javascript:void(0)" class="btn btn-primary float-end" data-bs-toggle="modal" data-bs-target="#add_treatment_modal">Add Treatment</a>
                    <livewire:treatment-table patientId="{{ $ipdPatientDepartment->patient->id }}" ipdId="{{ $ipdPatientDepartment->id }}" />
                </div>

                <!-- Notes -->
                <div class="tab-pane fade" id="ipdNotes">
                    <a href="btn btn-primary float-end" data-bs-toggle="modal" data-bs-target="#add_note_modal">Add Notes</a>
                    <livewire:notes-table patientId="{{ $ipdPatientDepartment->patient->id }}" ipdDiagnosisId="{{ $ipdPatientDepartment->id }}" />
                </div>

                <!-- Management Plan -->
                <div class="tab-pane fade" id="ipdManagementPlan">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h5 class="mb-0 text-primary">Management Plan</h5>
                        <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addManagementPlanModal">
                            <i class="fas fa-plus"></i> Add Plan
                        </button>
                    </div>
                    <livewire:management-plan-table patientId="{{ $ipdPatientDepartment->patient->id }}" ipdId="{{ $ipdPatientDepartment->id }}" />
                </div>
            </div>
        </div>
        <!-- End Diagnosis Tab -->

        <!-- Other Top-Level Tabs (unchanged) -->
        <div class="tab-pane fade" id="ipdDiagnosisNursingProgressReports">
            <a href="javascript:void(0)" class="btn btn-primary float-end" data-bs-toggle="modal" data-bs-target="#add_nursing_note_modal">
                Add Nursing Progress Notes
            </a>
            <livewire:nursing-progress-notes-table ipdId="{{ $ipdPatientDepartment->id }}" />
        </div>

        <div class="tab-pane fade" id="ipdConsultantInstruction">
            <livewire:ipd-consultant-register-table ipdDiagnosisId="{{ $ipdPatientDepartment->id }}" />
        </div>

        <div class="tab-pane fade" id="ipdOperation">
            <livewire:ipd-operation-table ipdOperationId="{{ $ipdPatientDepartment->id }}" />
        </div>

        <div class="tab-pane fade" id="ipdCharges">
            @if (!$ipdPatientDepartment->bill_status)
                <a href="javascript:void(0)" class="btn btn-primary float-end" data-bs-toggle="modal" data-bs-target="#addIpdChargesModal">
                    {{ __('messages.ipd_patient_charges.new_charge') }}
                </a>
            @endif
            <livewire:ipd-charge-table ipdDiagnosisId="{{ $ipdPatientDepartment->id }}" />
        </div>

        <div class="tab-pane fade" id="ipdTimelines">
            <div id="ipdTimelines"></div>
        </div>

        <div class="tab-pane fade" id="ipdVitals">
            <livewire:vitals-table ipdId="{{ $ipdPatientDepartment->id }}" />
        </div>

        <div class="tab-pane fade" id="ipdPayment">
            @if ($ipdPatientDepartment->bill && $ipdPatientDepartment->bill->net_payable_amount > 0) OR !$ipdPatientDepartment->bill)
                <a href="javascript:void(0)" class="btn btn-primary float-end" data-bs-toggle="modal" data-bs-target="#addIpdPaymentModal">
                    {{ __('messages.payment.new_payment') }}
                </a>
            @endif
            <livewire:ipd-payment-table ipdPatientDepartmentId="{{ $ipdPatientDepartment->id }}" />
        </div>

        <div class="tab-pane fade" id="showPatientPrescriptions">
            <livewire:patient-prescription-detail-table patientId="{{ $ipdPatientDepartment->patient_id }}" />
        </div>

        <div class="tab-pane fade" id="ipdBill">
            <div class="table-responsive">
                <div class="card">
                    <div class="card-body">
                        @include('ipd_bills.table')
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

<!-- PROFESSIONAL LAB REPORT MODAL -->
<div class="modal fade" id="professionalLabReportModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-fullscreen">
        <div class="modal-content border-0">
            <div class="modal-body p-0 bg-white" id="professionalReportContent">
                <div class="text-center py-5">
                    <div class="spinner-border text-primary" style="width:3rem;height:3rem;"></div>
                    <p class="mt-3">Loading report...</p>
                </div>
            </div>
            <div class="modal-footer bg-light no-print">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-success" onclick="window.print()">Print Report</button>
            </div>
        </div>
    </div>
</div>
{{-- ==== FIXED: VIEW LAB REPORTS TAB + MODAL + SCRIPT (NO MORE ERRORS) ==== --}}

<!-- VIEW LAB REPORTS TAB -->
<div class="tab-pane fade" id="ipdViewLabReports">
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-primary text-white py-3">
            <h5 class="mb-0">View Lab Reports</h5>
        </div>
        <div class="card-body p-4">
            @if($pathologyTests->isEmpty())
                <div class="alert alert-info text-center mb-0">
                    No laboratory reports found for this patient.
                </div>
            @else
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
                                    $items       = $test->pathologyTestItems;
                                    $testNames   = $items->pluck('pathologytesttemplate.test_name')->filter()->implode(', ');
                                    $testNames   = $testNames ?: 'Pathology Test';
                                    $doctorName  = $test->doctor?->doctorUser?->full_name ?? 'Not specified';
                                    $performedBy = $test->performed_by_user?->full_name ?? 'Laboratory Technician';
                                    $diagnosis   = $test->diagnosis ?? 'Routine Check';

                                    // Safely get specimen from the first template
                                    $firstTemplate = $items->first()?->pathologytesttemplate;
                                    $specimen = $firstTemplate?->test_type ?? 'BLOOD';
                                @endphp
                                <tr>
                                    <td>{{ $test->created_at->format('d/m/Y') }}</td>
                                    <td><strong>{{ $test->lab_number ?? 'LAB-'.$test->id }}</strong></td>
                                    <td>{{ Str::limit($testNames, 60) }}</td>
                                    <td>{{ $doctorName }}</td>
                                    <td class="text-center">
                                        <button type="button"
                                                class="btn btn-sm btn-info text-white"
                                                onclick='openProfessionalLabReport({!! json_encode([
                                                    "date"           => $test->created_at->format('d/m/Y'),
                                                    "lab_no"         => $test->lab_number ?? 'LAB-'.$test->id,
                                                    "patient_name"   => $patientName,
                                                    "age"            => $patientAge . " YRS",
                                                    "gender"         => $patientGender,
                                                    "diagnosis"      => $diagnosis,
                                                    "requested_test" => $testNames,
                                                    "clinician"      => $doctorName,
                                                    "performed_by"   => $performedBy,
                                                    "company"        => getCompanyName() ?: "CNML CLINIC",
                                                    "specimen"       => $specimen,
                                                    "testItems"      => $test->pathologyTestItems->map(function($item) use ($test) {
                                                        $template = $item->pathologytesttemplate;
                                                        $raw      = data_get($test->test_results, $item->id); // Safe access

                                                        // Extract result safely – handles all known formats
                                                        $result = "Not Done";
                                                        if ($raw !== null) {
                                                            if (is_array($raw) && isset($raw['value'])) {
                                                                $result = $raw['value'];
                                                            } elseif (is_array($raw) && count($raw) > 0) {
                                                                // Some tests store result directly in indexed array
                                                                $first = reset($raw);
                                                                $result = is_array($first) ? ($first['value'] ?? 'Not Done') : $first;
                                                            } elseif (!is_array($raw)) {
                                                                $result = $raw;
                                                            }
                                                        }

                                                        return [
                                                            'test_name'       => $template?->test_name ?? 'Unknown Test',
                                                            'result'          => $result,
                                                            'unit'            => $template?->unit ?? '',
                                                            'reference_range' => $template?->reference_range ?? 'N/A',
                                                        ];
                                                    })->toArray()
                                                ]) !!})'>
                                            View
                                        </button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>
</div>

<!-- PROFESSIONAL LAB REPORT MODAL -->
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

<script>
function openProfessionalLabReport(data) {
    const body = document.getElementById('professionalReportContent');
    const today = new Date().toLocaleDateString('en-GB');

    let rows = '';
    data.testItems.forEach(item => {
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
            <small class="text-muted">${data.company}</small>
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
            ${data.requested_test.toUpperCase()}
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

<!-- MANAGEMENT PLAN MODAL -->
<div class="modal fade" id="addManagementPlanModal" tabindex="-1" aria-labelledby="addManagementPlanModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addManagementPlanModalLabel">Add Management Plan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            {{ Form::open(['id' => 'addManagementPlanForm']) }}
            <div class="modal-body">
                <div class="alert alert-danger d-none hide" id="addManagementPlanErrorsBox"></div>
                
                {{ Form::hidden('ipd_id', $ipdPatientDepartment->id) }}
                {{ Form::hidden('patient_id', $ipdPatientDepartment->patient_id) }}

                <div class="mb-3">
                    {{ Form::label('management_plan', 'Management Plan:', ['class' => 'form-label']) }}
                    {{ Form::textarea('management_plan', null, ['class' => 'form-control', 'required', 'rows' => 5, 'placeholder' => 'Enter management plan']) }}
                </div>
            </div>
            <div class="modal-footer">
                {{ Form::button(__('messages.common.save'), ['type' => 'submit', 'class' => 'btn btn-primary me-3', 'id' => 'btnManagementPlanSave', 'data-loading-text' => "<span class='spinner-border spinner-border-sm'></span> Processing..."]) }}
                <button type="button" id="btnManagementPlanCancel" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('messages.common.cancel') }}</button>
            </div>
            {{ Form::close() }}
        </div>
    </div>
</div>

<script>
listenSubmit('#addManagementPlanForm', function (event) {
    event.preventDefault()
    let loadingButton = jQuery(this).find('#btnManagementPlanSave')
    loadingButton.button('loading')
    let data = {
        formSelector: $(this),
        url: "{{ route('management_plans.store') }}",
        type: 'POST'
    }
    newRecord(data, loadingButton, '#addManagementPlanModal')
    loadingButton.attr('disabled', false)
})
</script>

{{-- ==== END OF FIX ==== --}}