
<div>
    @if ($opdPatientDepartment->served == 0)
        <!-- Discharge Reminder Note -->
        <div class="alert alert-danger d-flex align-items-center mb-4" role="alert">
            <i class="fas fa-exclamation-triangle me-2"></i>
            <div>
                <strong>Note: Don't forget to Click Discharge</strong>
            </div>
        </div>
    @endif
    
    <div class="bg-transparent">
        <div class="">
            <div class="row">
                <div class="col-xxl-5 col-12 mb-5">
                    <div class="d-sm-flex align-items-center mb-5 mb-xxl-0 text-center text-sm-start">
                        {{-- <div class="image image-circle image-small">
                            <img src="{{ $opdPatientDepartment->patient->patientUser->image_url }}"
                                class="object-fit-cover" alt="image" />
                        </div> --}}
                        <div class="ms-0 ms-md-10 mt-5 mt-sm-0">
                            <span
                                class="badge bg-light-warning mb-2">{{ !empty($opdPatientDepartment->opd_number) ? '#' . $opdPatientDepartment->opd_number : __('messages.common.n/a') }}</span>

                            @if ($opdPatientDepartment->served == 0)
                                <span class="badge bg-light-danger">Not Discharged</span>
                            @else
                                <span class="badge bg-light-success">Discharged</span>
                            @endif



                            {{-- <h2><a href="#"
                                    class="text-decoration-none">{{ $opdPatientDepartment->patient->patientUser->full_name }}</a>
                            </h2>
                            <a href="mailto:{{ $opdPatientDepartment->patient->patientUser->email }}"
                                class="text-gray-600 text-decoration-none fs-5">
                                {{ $opdPatientDepartment->patient->patientUser->email }}
                            </a> --}}
                            {{-- <sapn class="d-flex align-items-center me-5 mb-2 mt-2">
                                @if (
                                    !empty($opdPatientDepartment->patient->address->address1) ||
                                        !empty($opdPatientDepartment->patient->address->address2) ||
                                        !empty($opdPatientDepartment->patient->address->city) ||
                                        !empty($opdPatientDepartment->patient->address->zip))
                                    <span><i class="fas fa-location"></i></span>
                                @endif
                                <span class="p-2">
                                    {{ !empty($opdPatientDepartment->patient->address->address1) ? $opdPatientDepartment->patient->address->address1 : '' }}{{ !empty($opdPatientDepartment->patient->address->address2) ? (!empty($opdPatientDepartment->patient->address->address1) ? ',' : '') : '' }}
                                    {{ empty($opdPatientDepartment->patient->address->address1) || !empty($opdPatientDepartment->patient->address->address2) ? (!empty($opdPatientDepartment->patient->address->address2) ? $opdPatientDepartment->patient->address->address2 : '') : '' }}
                                    {{ empty($opdPatientDepartment->patient->address->address1) && empty($opdPatientDepartment->patient->address->address2) ? '' : '' }}{{ !empty($opdPatientDepartment->patient->address->city) ? ',' . $opdPatientDepartment->patient->address->city : '' }}{{ !empty($opdPatientDepartment->patient->address->zip) ? ',' . $opdPatientDepartment->patient->address->zip : '' }}
                                </span>
                            </span> --}}
                        </div>
                    </div>
                </div>
                <div class="col-xxl-7 col-12">
                    <div class="row mb-3 justify-content-end">
                        <div class="col-md-5 d-flex justify-content-center align-items-center" style="text-align: end,">

                            <form id="served-form"
                                action="{{ route('opd.patient.mark-served', ['id' => $opdPatientDepartment->id]) }}"
                                method="POST" class="d-none">
                                @csrf
                            </form>
                            @if ($opdPatientDepartment->served == 0)
                                <a href="#"
                                    onclick="event.preventDefault();document.getElementById('served-form').submit();"
                                    class="btn btn-success btn-sm me-3">Discharge</a>
                            @else
                                <a href="#"
                                    onclick="event.preventDefault();document.getElementById('served-form').submit();"
                                    class="btn btn-success btn-sm me-3">Mark As Not Discharged</a>
                            @endif
                            <a href="{{ route('ipd.patient.create', ['ref_p_id' => $opdPatientDepartment->patient->id]) }}"
                                class="btn btn-warning btn-sm">Admit Patient</a>
                        </div>
                    </div>
                    {{-- <div class="row justify-content-center">
                        <div class="col-md-4 col-sm-6 col-12 mb-6 mb-md-0">
                            <div class="border rounded-10 p-5 h-100">
                                <h2 class="text-primary mb-3">
                                    {{ !empty($opdPatientDepartment->patient->cases) ? $opdPatientDepartment->patient->cases->count() : 0 }}
                                </h2>
                                <h3 class="fs-5 fw-light text-gray-600 mb-0">{{ __('messages.patient.total_cases') }}
                                </h3>
                            </div>
                        </div>
                        <div class="col-md-4 col-sm-6 col-12 mb-6 mb-md-0">
                            <div class="border rounded-10 p-5 h-100">
                                <h2 class="text-primary mb-3">
                                    {{ !empty($opdPatientDepartment->patient->admissions) ? $opdPatientDepartment->patient->admissions->count() : 0 }}
                                </h2>
                                <h3 class="fs-5 fw-light text-gray-600 mb-0">
                                    {{ __('messages.patient.total_admissions') }}</h3>
                            </div>
                        </div>
                        <div class="col-md-4 col-sm-6 col-12">
                            <div class="border rounded-10 p-5 h-100">
                                <h2 class="text-primary mb-3">
                                    {{ !empty($opdPatientDepartment->patient->appointments) ? $opdPatientDepartment->patient->appointments->count() : 0 }}
                                </h2>
                                <h3 class="fs-5 fw-light text-gray-600 mb-0">
                                    {{ __('messages.patient.total_appointments') }}</h3>
                            </div>
                        </div>
                    </div> --}}
                </div>
            </div>
        </div>
    </div>

    @include('opd_patient_departments.vitals-indicator')
    <div class="mt-7 overflow-hidden">
        <ul class="nav nav-tabs mb-5 pb-1 overflow-auto flex-nowrap justify-content-between text-nowrap" id="myTab" role="tablist">
            <li class="nav-item position-relative me-7 mb-3" role="presentation">
                <button class="nav-link active p-0" id="opdPatientOverview" data-bs-toggle="tab"
                    data-bs-target="#opdOverview" type="button" role="tab" aria-controls="overview"
                    aria-selected="true">
                    <i class="fas fa-chart-pie"></i>
                    {{ __('messages.overview') }}
                    
                </button>
            </li>
            <li class="nav-item position-relative me-7 mb-3" role="presentation">
                <button class="nav-link p-0" id="opdVisitTab" data-bs-toggle="tab" data-bs-target="#opdVisits"
                    type="button" role="tab" aria-controls="cases" aria-selected="false">
                    <i class="fas fa-calendar-check"></i>
                    {{ __('messages.opd_patient.visits') }}
                </button>
            </li>
            <li class="nav-item position-relative me-7 mb-3" role="presentation">
                <button class="nav-link p-0" id="opdDiagnosisTab" data-bs-toggle="tab" data-bs-target="#opdDiagnosis"
                    type="button" role="tab" aria-controls="patients" aria-selected="false">
                    <i class="fas fa-stethoscope"></i>
                    {{ __('messages.opd_diagnosis') }}
                </button>
            </li>
            <li class="nav-item position-relative me-7 mb-3" role="presentation">
                <button class="nav-link p-0" id="opdDiagnosisNursingProgressReports-tab" data-bs-toggle="tab"
                    data-bs-target="#opdDiagnosisNursingProgressReports" type="button" role="tab"
                    aria-controls="NursingProgressReports" aria-selected="true">
                    <i class="fas fa-notes-medical"></i>
                    Nurses Notes
                </button>
            </li>

            <li class="nav-item position-relative me-7 mb-3" role="presentation">
                <button class="nav-link p-0" id="opdPatientsTimelinesTab" data-bs-toggle="tab"
                    data-bs-target="#opdPatientsTimelines" type="button" role="tab" aria-controls="patients"
                    aria-selected="false">
                    <i class="fas fa-stream"></i>
                    {{ __('messages.opd_timelines') }}
                </button>
            </li>
            <li class="nav-item position-relative me-7 mb-3" role="presentation">
                <button class="nav-link p-0" id="opdPatientsVitalsTab" data-bs-toggle="tab"
                    data-bs-target="#opdPatientsVitals" type="button" role="tab" aria-controls="patients"
                    aria-selected="false">
                    <i class="fas fa-heartbeat"></i>
                    {{ __('messages.vitals') }}
                </button>
            </li>
            @if($user->gender != 0)
                <li class="nav-item position-relative me-7 mb-3" role="presentation">
                    <button class="nav-link p-0" id="opdPatientsAntenatalsTab" data-bs-toggle="tab"
                        data-bs-target="#opdPatientsAntenatals" type="button" role="tab" aria-controls="patients"
                        aria-selected="false">
                        <i class="fas fa-female"></i>
                        {{ __('messages.antenatal.title') }}
                    </button>
                </li>
                <li class="nav-item position-relative me-7 mb-3" role="presentation">
                    <button class="nav-link p-0" id="opdPatientsPostnatalsTab" data-bs-toggle="tab"
                        data-bs-target="#opdPatientsPostnatals" type="button" role="tab" aria-controls="opd-postnatals"
                        aria-selected="false">
                        <i class="fas fa-baby"></i>
                        {{ __('messages.postnatal.postnatal_history') }}
                    </button>
                </li>
                <li class="nav-item position-relative me-7 mb-3" role="presentation">
                    <button class="nav-link p-0" id="opdObstetricsTab" data-bs-toggle="tab"
                        data-bs-target="#opdPatientsObstetric" type="button" role="tab" aria-controls="opd-obstetrics"
                        aria-selected="false">
                        <i class="fa fa-calendar-check me-2"></i>
                        {{ __('messages.previous_obstetric_history.previous_obstetric_history') }}
                    </button>
                </li>
            @endif
            <li class="nav-item position-relative me-7 mb-3">
                <a class="nav-link p-0" data-bs-toggle="tab"
                    href="#showPatientPrescriptions"><i class="fas fa-prescription-bottle-alt"></i> {{ __('messages.prescriptions') }}</a>
            </li>
            <li class="nav-item position-relative me-7 mb-3" role="presentation">
                <a href="{{ route('patients.show', ['patient' => $opdPatientDepartment->patient->id]) }}" class="nav-link p-0" id="opdPatientsVitalsTab">
                    <i class="fas fa-user-circle"></i>
                    View Profile
                </a>
            </li>

        </ul>
        <div class="tab-content" id="myTabContent">
            <div class="tab-pane fade show active" id="opdOverview" role="tabpanel"
                aria-labelledby="opdPatientOverview">
                <div class="card">
                    <div class="card-body d-flex justify-content-center me-2">
                        <div class="row">
                            <div class="col-12">
                                <div class="row">
                                    <h2 class="mb-0">
                                        <a href="{{ route('patients.show', $opdPatientDepartment->patient->id) }}"
                                            class="text-decoration-none">
                                            {{ $opdPatientDepartment->patient->patientUser->full_name }}
                                        </a>
                                    </h2>
                                </div>
                                <hr>
                                <div class="row align-items-center">
                                    <div class="col-lg-3 text-center">
                                        <div class="image image-circle image-small">
                                            <img src="{{ $opdPatientDepartment->patient->patientUser->image_url }}"
                                                alt="image" />
                                        </div>
                                    </div>
                                    <div class="col-lg-9">
                                        <table class="table  mb-0">
                                            <tbody>
                                                <tr>
                                                    <td>{{ __('messages.user.gender') }}</td>
                                                    <td>{{ $opdPatientDepartment->patient->patientUser->gender == 0 ? 'Male' : 'Female' }}
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>{{ __('messages.user.email') }}</td>
                                                    <td class="text-break w-75">
                                                        {{ $opdPatientDepartment->patient->patientUser->email }}
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>{{ __('messages.user.phone') }}</td>
                                                    <td>{{ $opdPatientDepartment->patient->patientUser->phone ?? __('messages.common.n/a') }}
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                <hr>
                                <div class="row">
                                    <div class="col-lg-9">
                                        <table class="table table-bordered">
                                            <tbody>
                                                <tr>
                                                    <td>{{ __('messages.case.case_id') }}</td>
                                                    <td>{{ !empty($opdPatientDepartment->patientCase) ? $opdPatientDepartment->patientCase->case_id : __('messages.common.n/a') }}
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>{{ __('messages.opd_patient.opd_number') }}</td>
                                                    <td>{{ $opdPatientDepartment->opd_number }}</td>
                                                </tr>
                                                <tr>
                                                    <td class="white-space-nowrap" width="40%">
                                                        {{ __('messages.opd_patient.admission_date') }}</td>
                                                    <td>{{ \Carbon\Carbon::parse($opdPatientDepartment->admission_date)->translatedFormat('jS M, Y') }}
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>{{ __('messages.opd_patient.bed_id') }}</td>
                                                    <td>
                                                        {{-- {{ $opdPatientDepartment->bed->name }} --}}
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>

                                </div>
                                <hr>
                                <div class="row">
                                    <p><i class="fa fa-tag"></i> {{ __('messages.opd_patient.symptoms') }}</p>
                                    <ul class="timeline-ps-46 mb-0">
                                        <li>
                                            <div>
                                                {!! !empty($opdPatientDepartment->symptoms)
                                                    ? nl2br(e($opdPatientDepartment->symptoms))
                                                    : __('messages.common.n/a') !!}
                                            </div>
                                        </li>
                                    </ul>
                                </div>
                                <hr>
                                {{-- <div class="row mb-2">
                                    <div class="col-lg-10">
                                        <h3 class="text-uppercase fs-5">
                                            {{ __('messages.opd_patient_consultant_register.consultant_doctor') }}</h3>
                                    </div>
                                    <div class="col-lg-2 text-end">
                                        <a href="javascript:void(0)" data-bs-toggle="modal"
                                            data-bs-target="#addConsultantInstructionModal">
                                            <i class="fa fa-plus text-dark"></i>
                                        </a>
                                    </div>
                                </div> --}}
                                {{-- <div id="consultant-div">
                                    @if (count($consultantDoctor) == 0)
                                        <tr class="text-center">
                                            <td colspan="4">
                                                <div class="mb-5">
                                                    {{ __('messages.common.no') . ' ' . __('messages.opd_consultant_doctor') }}
                                                </div>
                                            </td>
                                        </tr>
                                    @else
                                        @foreach ($consultantDoctor as $register)
                                            <div class="d-flex justify-content-between">
                                                <div class="row">
                                                    <div class="col-12">
                                                        <div class="d-flex align-items-center">
                                                            <div class="image image-mini me-3">
                                                                <a
                                                                    href="{{ route('doctors_show', $register->doctor->id) }}">
                                                                    <div class="">
                                                                        <img src="{{ $register->doctor->doctorUser->image_url }}"
                                                                            alt=""
                                                                            class="user-img rounded-circle object-contain image">
                                                                    </div>
                                                                </a>
                                                            </div>
                                                            <div class="d-flex flex-column">
                                                                <a href="{{ route('doctors_show', $register->doctor->id) }}"
                                                                    class="mb-1 text-decoration-none">{{ $register->doctor->doctorUser->full_name }}</a>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row align-items-center">
                                                    <div class="col-1 text-end">
                                                        <a class="cursor-pointer delete-consultant-doctor-btn"
                                                            data-id="{{ $register->id }}"><i
                                                                class="fa fa fa-times text-danger"></i></a>
                                                    </div>
                                                </div>
                                            </div>
                                            <hr>
                                        @endforeach
                                    @endif
                                </div> --}}

                                <div class="row" id="overviewOpdTimeline">
                                    <div class="mb-5">
                                        <h3 class="text-uppercase fs-5">
                                            {{ __('messages.opd_patient_timeline.timeline') }}</h3>
                                    </div>
                                    @forelse($opdTimeline as $timeline)
                                        <div class="timeline-date">
                                            <span
                                                class="bg-primary text-white py-1 px-3 rounded-5 fs-6">{{ \Carbon\Carbon::parse($timeline->date)->translatedFormat('d.m.Y') }}</span>
                                        </div>
                                        <div class="row timeline-before mt-5">
                                            <div class="col-1 d-flex justify-content-end pe-0">
                                                <div class="list-icon">
                                                    <i class="fa fa-list-alt"></i>
                                                </div>
                                            </div>
                                            <div class="col-11 ps-5">
                                                <h3 class="t-heading mb-0"> {{ $timeline->title }} </h3>
                                                <div class="t-table border-top-0 mb-5 opd-timeline-desc">
                                                    {{ $timeline->description ?? __('messages.common.n/a') }}
                                                </div>
                                            </div>
                                        </div>
                                    @empty
                                        <div class="mb-5">
                                            {{ __('messages.opd_patient_timeline.no_timeline_found') }}</div>
                                    @endforelse
                                    @if (count($opdTimeline) != 0)
                                        <div class="col-1 pe-0 ps-5  d-flex justify-content-center">
                                            <div class="list-icon bg-light">
                                                <i class="fa fa-clock text-primary"></i>
                                            </div>
                                        </div>
                                    @endif
                                </div>

                                {{-- <div class="mb-10 mt-5">
                                    <div class="d-flex justify-content-between">
                                        <h3 class="text-uppercase fs-5 mb-4">{{ __('messages.operation.operation') }}
                                        </h3>
                                        @if (App\Models\OpdOperation::where('opd_patient_department_id', $opdPatientDepartment->id)->count() > 5)
                                            <ul class="nav mb-4" id="myTab" role="tablist">
                                                <li class="nav-item">
                                                    <a class="nav-link opd-operation-btn btn btn-primary btn-sm text-capitalize text-white"
                                                        data-bs-toggle="tab" data-bs-target="#opdOperation"
                                                        id="cases-tab" type="button" role="tab"
                                                        aria-controls="cases"
                                                        aria-selected="false">{{ __('messages.common.view') }}</a>
                                                </li>
                                            </ul>
                                        @endif
                                    </div>
                                    <livewire:overview-opd-operation-table
                                        opdOperationId="{{ $opdPatientDepartment->id }}" />
                                </div> --}}
                            </div>
                            {{-- <div class="col-6">
                                <div class="mb-10">
                                    <div class="d-flex justify-content-between">
                                        <h3 class="text-uppercase fs-5 mb-4">{{ __('messages.payment.payment') }}
                                            / {{ __('messages.billing') }}</h3>
                                        @if ($bill['total_payment'] && $bill['total_charges'] != 0)
                                            <h5 class="text-gray-700">
                                                {{ round(($bill['total_payment'] / $bill['total_charges']) * 100, 2) }}
                                                %</h5>
                                    </div>
                                    <div class="progress">
                                        <div class="progress-bar bg-primary" role="progressbar"
                                            style="width: {{ round(($bill['total_payment'] / $bill['total_charges']) * 100, 2) }}%"
                                            aria-valuenow="{{ round(($bill['total_payment'] / $bill['total_charges']) * 100, 2) }}"
                                            aria-valuemin="0" aria-valuemax="100"></div>
                                    </div>
                                @else
                                    <h5 class="text-gray-700">0%</h5>
                                </div>
                                <div class="progress">
                                    <div class="progress-bar bg-primary" role="progressbar" style="width: 0%"
                                        aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
                                </div>
                                @endif
                            </div> --}}
                            {{-- <div class="mb-10">
                                <div class="d-flex justify-content-between">
                                    <h3 class="text-uppercase fs-5">{{ __('messages.prescription.prescription') }}
                                    </h3>
                                    @if (App\Models\OpdPrescription::where('opd_patient_department_id', $opdPatientDepartment->id)->count() > 5)
                                        <ul class="nav" id="myTab" role="tablist">
                                            <li class="nav-item">
                                                <a class="nav-link opd-prescription-btn btn btn-primary btn-sm text-capitalize text-white"
                                                    data-bs-toggle="tab" data-bs-target="#opdPrescriptions"
                                                    id="cases-tab" type="button" role="tab"
                                                    aria-controls="cases" aria-selected="false">view all</a>
                                            </li>
                                        </ul>
                                    @endif
                                </div>
                                @if (App\Models\OpdPrescription::where('opd_patient_department_id', $opdPatientDepartment->id)->count() > 0)
                                    <livewire:overview-opd-prescription-table
                                        opdPrescriptionId="{{ $opdPatientDepartment->id }}" />
                                @else
                                    <table class="table table-striped">
                                        <thead class="">
                                            <tr>
                                                <th scope="col" class="">
                                                    {{ __('messages.opd_patient.opd_number') }}
                                                </th>

                                                <th scope="col" class="">
                                                    {{ __('messages.common.created_on') }}
                                                </th>
                                            </tr>
                                        </thead>
                                        <tbody class="">
                                            <tr>
                                                <td class="text-center" colspan="2">
                                                    {{ __('messages.no_data_available') }}
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                @endif
                            </div> --}}
                            {{-- <div class="mb-10">
                                <div class="d-flex justify-content-between">
                                    <h3 class="text-uppercase fs-5">
                                        {{ __('messages.opd_patient_consultant_register.consultant_instruction') }}
                                    </h3>
                                    @if (App\Models\OpdConsultantRegister::where('opd_patient_department_id', $opdPatientDepartment->id)->count() > 5)
                                        <ul class="nav" id="myTab" role="tablist">
                                            <li class="nav-item">
                                                <a class="nav-link opd-consultant-btn btn btn-primary btn-sm text-capitalize text-white"
                                                    data-bs-toggle="tab" data-bs-target="#opdConsultantInstruction"
                                                    id="cases-tab" type="button" role="tab"
                                                    aria-controls="cases" aria-selected="false">view all</a>
                                            </li>
                                        </ul>
                                    @endif
                                </div>
                                @if (App\Models\OpdConsultantRegister::where('opd_patient_department_id', $opdPatientDepartment->id)->count() > 0)
                                    <livewire:overview-opd-consultant-table
                                        opdConsultantId="{{ $opdPatientDepartment->id }}" />
                                @else
                                    <table class="table table-striped">
                                        <thead class="">
                                            <tr>
                                                <th scope="col" class="">
                                                    {{ __('messages.investigation_report.doctor') }}
                                                </th>

                                                <th scope="col" class="">
                                                    {{ __('messages.opd_patient_consultant_register.applied_date') }}
                                                </th>

                                                <th scope="col" class="">
                                                    {{ __('messages.opd_patient_consultant_register.instruction_date') }}
                                                </th>
                                            </tr>
                                        </thead>
                                        <tbody class="">
                                            <tr>
                                                <td class="text-center" colspan="6">
                                                    {{ __('messages.no_data_available') }}
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                @endif
                            </div>
                            <div class="mb-10">
                                <div class="d-flex justify-content-between">
                                    <h3 class="text-uppercase fs-5">{{ __('messages.charges') }}</h3>
                                    @if (App\Models\OpdCharge::where('opd_patient_department_id', $opdPatientDepartment->id)->count() > 5)
                                        <ul class="nav" id="myTab" role="tablist">
                                            <li class="nav-item">
                                                <a class="nav-link opd-charges-btn btn btn-primary btn-sm text-capitalize text-white"
                                                    data-bs-toggle="tab" data-bs-target="#opdCharges" type="button"
                                                    role="tab" aria-controls="cases" aria-selected="false">view
                                                    all</a>
                                            </li>
                                        </ul>
                                    @endif
                                </div>
                                @if (App\Models\OpdCharge::where('opd_patient_department_id', $opdPatientDepartment->id)->count() > 0)
                                    <livewire:overview-opd-charges-table
                                        opdChargeId="{{ $opdPatientDepartment->id }}" />
                                @else
                                    <table class="table table-striped">
                                        <thead class="">
                                            <tr>
                                                <th scope="col" class="">
                                                    {{ __('messages.investigation_report.date') }}
                                                </th>

                                                <th scope="col" class="">
                                                    {{ __('messages.charge_category.charge_type') }}
                                                </th>

                                                <th scope="col" class="">
                                                    {{ __('messages.charge.code') }}
                                                </th>

                                                <th scope="col" class="">
                                                    {{ __('messages.charge.standard_charge') }}
                                                </th>

                                                <th scope="col" class="">
                                                    {{ __('messages.opd_patient_charges.applied_charge') }}
                                                </th>
                                            </tr>
                                        </thead>
                                        <tbody class="">
                                            <tr>
                                                <td class="text-center" colspan="6">
                                                    {{ __('messages.no_data_available') }}
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                @endif
                            </div>
                            <div class="mb-10">
                                <div class="d-flex justify-content-between">
                                    <h3 class="text-uppercase fs-5">{{ __('messages.payment.payment') }}</h3>
                                    @if (App\Models\OpdPayment::whereOpdPatientDepartmentId($opdPatientDepartment->id)->count() > 5)
                                        <ul class="nav" id="myTab" role="tablist">
                                            <li class="nav-item">
                                                <a class="nav-link opd-payment-btn btn btn-primary btn-sm text-capitalize text-white"
                                                    data-bs-toggle="tab" data-bs-target="#opdPayment" type="button"
                                                    role="tab" aria-controls="cases" aria-selected="false">view
                                                    all</a>
                                            </li>
                                        </ul>
                                    @endif
                                </div>
                                @if (App\Models\OpdPayment::whereOpdPatientDepartmentId($opdPatientDepartment->id)->count() > 0)
                                    <livewire:overview-opd-payment-table
                                        opdPaymentId="{{ $opdPatientDepartment->id }}" />
                                @else
                                    <table class="table table-striped">
                                        <thead class="">
                                            <tr>
                                                <th scope="col" class="">
                                                    {{ __('messages.investigation_report.date') }}
                                                </th>

                                                <th scope="col" class="">
                                                    {{ __('messages.ambulance_call.amount') }}
                                                </th>

                                                <th scope="col" class="">
                                                    {{ __('messages.opd_payments.payment_mode') }}
                                                </th>

                                                <th scope="col" class="">
                                                    {{ __('messages.document.document') }}
                                                </th>

                                                <th scope="col" class="">
                                                    {{ __('messages.ambulance.note') }}
                                                </th>
                                            </tr>
                                        </thead>
                                        <tbody class="">
                                            <tr>
                                                <td class="text-center" colspan="6">
                                                    {{ __('messages.no_data_available') }}
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                @endif
                            </div> --}}
                            <div class="mb-10">
                                <div class="d-flex justify-content-between">
                                    <h3 class="text-uppercase fs-5">
                                        {{ __('messages.patient_diagnosis_test.diagnosis') }}</h3>
                                    @if (App\Models\OpdDiagnosis::whereOpdPatientDepartmentId($opdPatientDepartment->id)->count() > 5)
                                        <ul class="nav" id="myTab" role="tablist">
                                            <li class="nav-item">
                                                <a class="nav-link opd-diagnosis-btn btn btn-primary btn-sm text-capitalize text-white"
                                                    data-bs-toggle="tab" data-bs-target="#opdDiagnosis"
                                                    id="cases-tab" type="button" role="tab"
                                                    aria-controls="cases" aria-selected="false">view
                                                    all</a>
                                            </li>
                                        </ul>
                                    @endif
                                </div>
                                @if (App\Models\OpdDiagnosis::whereOpdPatientDepartmentId($opdPatientDepartment->id)->count() > 0)
                                    @php
                                        try {
                                            echo \Livewire\Livewire::mount('overview-opd-diagnosis-table', ['opdDiagnosisId' => $opdPatientDepartment->id])->html();
                                        } catch (\Exception $e) {
                                        
                                            // \Log::error($e->getMessage());
                                        }
                                    @endphp
                                    {{--
                                    <livewire:overview-opd-diagnosis-table
                                        opdDiagnosisId="{{ $opdPatientDepartment->id }}" />
                                        --}}
                                @else
                                    <table class="table table-striped">
                                        <thead class="">
                                            <tr>
                                                <th scope="col" class="">
                                                    {{ __('messages.opd_patient_diagnosis.report_type') }}
                                                </th>

                                                <th scope="col" class="">
                                                    {{ __('messages.opd_patient_diagnosis.report_date') }}
                                                </th>

                                                <th scope="col" class="">
                                                    {{ __('messages.document.document') }}
                                                </th>

                                                <th scope="col" class="">
                                                    {{ __('messages.opd_patient_diagnosis.description') }}
                                                </th>
                                            </tr>
                                        </thead>
                                        <tbody class="">
                                            <tr>
                                                <td class="text-center" colspan="5">
                                                    {{ __('messages.no_data_available') }}
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="tab-pane fade" id="opdVisits" role="tabpanel" aria-labelledby="opdVisitTab">
                <a href="{{ route('opd.patient.create') . '?revisit=' . $opdPatientDepartment->id }}"
                    class="btn btn-primary float-end">
                    {{ __('messages.opd_patient.revisits') }}
                </a>
                <livewire:opd-patient-visitor-table opdPatientDepartment="{{ $opdPatientDepartment->patient_id }}"
                    opdPatientDepartmentId="{{ $opdPatientDepartment->id }}" />
            </div>
            <div class="tab-pane fade" id="opdDiagnosis" role="tabpanel" aria-labelledby="opdDiagnosisTab">
                <ul class="nav nav-tabs mb-5 pb-1 overflow-auto flex-nowrap text-nowrap" id="myDiagnosticsTab"
                    role="tablist">
                    <li class="nav-item position-relative me-7 mb-3" role="presentation">
                        <button class="nav-link active p-0" id="opdDiagnosisComplaints-tab" data-bs-toggle="tab"
                            data-bs-target="#opdDiagnosisComplaints" type="button" role="tab"
                            aria-controls="Complaints" aria-selected="true">
                            Complaints
                        </button>
                    </li>

                    <li class="nav-item position-relative me-7 mb-3" role="presentation">
                        <button class="nav-link p-0" id="opdDiagnosisGeneralExamination-tab" data-bs-toggle="tab"
                            data-bs-target="#opdDiagnosisGeneralExamination" type="button" role="tab"
                            aria-controls="GeneralExamination" aria-selected="true">
                            Examination
                        </button>
                    </li>

                    {{-- <li class="nav-item position-relative me-7 mb-3" role="presentation">
                        <button class="nav-link p-0" id="opdDiagnosisSystemicExamination-tab" data-bs-toggle="tab"
                            data-bs-target="#opdDiagnosisSystemicExamination" type="button" role="tab"
                            aria-controls="SystemicExamination" aria-selected="true">
                            Systemic Examination
                        </button>
                    </li> --}}


                    <li class="nav-item position-relative me-7 mb-3" role="presentation">
                        <button class="nav-link p-0" id="opdDiagnosisDiagnosis-tab" data-bs-toggle="tab"
                            data-bs-target="#opdDiagnosisDiagnosis" type="button" role="tab"
                            aria-controls="Diagnosis" aria-selected="true">
                            Diagnosis
                        </button>
                    </li>
                    
                    <li class="nav-item position-relative me-7 mb-3" role="presentation">
                        <button class="nav-link p-0" id="opdProvisionalDiagnosis-tab" data-bs-toggle="tab"
                            data-bs-target="#opdProvisionalDiagnosis" type="button" role="tab"
                            aria-controls="ProvisionalDiagnosis" aria-selected="true">
                            Provisional Diagnosis
                        </button>
                    </li>
                    <li class="nav-item position-relative me-7 mb-3" role="presentation">
                        <button class="nav-link p-0" id="cases-tab" data-bs-toggle="tab"
                            data-bs-target="#opdPathology" type="button" role="tab" aria-controls="cases"
                            aria-selected="false">
                            Pathology
                        </button>
                    </li>
                    <li class="nav-item position-relative me-7 mb-3" role="presentation">
                        <button class="nav-link p-0" id="cases-tab" data-bs-toggle="tab"
                            data-bs-target="#opdRadiology" type="button" role="tab" aria-controls="cases"
                            aria-selected="false">
                            Radiology
                        </button>
                    </li>

                    <li class="nav-item position-relative me-7 mb-3" role="presentation">
                        <button class="nav-link p-0" id="cases-tab" data-bs-toggle="tab"
                            data-bs-target="#opdTreatment" type="button" role="tab" aria-controls="cases"
                            aria-selected="false">
                            Treatment
                        </button>
                    </li>

                    <li class="nav-item position-relative me-7 mb-3" role="presentation">
                        <button class="nav-link p-0" id="cases-tab" data-bs-toggle="tab"
                            data-bs-target="#opdNotes" type="button" role="tab" aria-controls="cases"
                            aria-selected="false">
                            Notes
                        </button>
                    </li>


                </ul>

                <div class="tab-content" id="myDiagnosticsTabContent">


                    <div class="tab-pane fade  show active opdDiagnosisComplaints" id="opdDiagnosisComplaints"
                        role="tabpanel" aria-labelledby="opdDiagnosisComplaints-tab">
                        <a href="javascript:void(0)" class="btn btn-primary float-end" data-bs-toggle="modal"
                            data-bs-target="#add_complaint_modal">
                            Add Complaint
                        </a>
                        <livewire:complaints-table opdId="{{ $opdPatientDepartment->id }}" />
                    </div>
                    <div class="tab-pane fade opdDiagnosisGeneralExamination" id="opdDiagnosisGeneralExamination"
                        role="tabpanel" aria-labelledby="opdDiagnosisGeneralExamination-tab">
                        <a href="javascript:void(0)" class="btn btn-primary float-end" data-bs-toggle="modal"
                            data-bs-target="#add_general_examination_modal">
                            Add Examination
                        </a>
                        <livewire:general-examination-table opdId="{{ $opdPatientDepartment->id }}" />
                    </div>
                    {{-- <div class="tab-pane fade opdDiagnosisSystemicExamination" id="opdDiagnosisSystemicExamination"
                        role="tabpanel" aria-labelledby="opdDiagnosisSystemicExamination-tab">
                        <a href="javascript:void(0)" class="btn btn-primary float-end" data-bs-toggle="modal"
                            data-bs-target="#add_systemic_examination_modal">
                            Add Systemic Examination
                        </a>
                        <livewire:systemic-examination-table opdId="{{ $opdPatientDepartment->id }}" />
                    </div> --}}
                    <div class="tab-pane fade opdDiagnosisDiagnosis" id="opdDiagnosisDiagnosis" role="tabpanel"
                        aria-labelledby="opdDiagnosisDiagnosis-tab">
                        
                        <a href="javascript:void(0)" class="btn btn-primary float-end" data-bs-toggle="modal"
                            data-bs-target="#add_opd_diagnoses_modal">
                            Add diagnosis
                        </a>
                        <livewire:opd-diagnoses-table opdDiagnoses="{{ $opdPatientDepartment->id }}" />
                    </div>
                    
                    {{--
                    
                    <div class="tab-pane fade opdProvisionalDiagnosis" id="opdProvisionalDiagnosis" role="tabpanel"
                        aria-labelledby="opdProvisionalDiagnosis-tab">
                        
                        <a href="javascript:void(0)" class="btn btn-primary float-end" data-bs-toggle="modal"
                            data-bs-target="#add_opd_provisional_diagnoses_modal">
                            Add diagnosis
                        </a>
                        <livewire:opd-provisional-diagnoses-table opdProvisionalDiagnoses="{{ $opdPatientDepartment->id }}" />
                    </div>
--}}
<div class="tab-pane fade opdProvisionalDiagnosis" id="opdProvisionalDiagnosis" role="tabpanel"
                    aria-labelledby="opdProvisionalDiagnosis-tab">
   
                    <livewire:opd-provisional-diagnosis-table opdProvisionalDiagnosisId="{{ $opdPatientDepartment->id }}" />
                </div>





                    <div class="tab-pane fade opdPathology" id="opdPathology" role="tabpanel"
                        aria-labelledby="cases-tab">
 
                        <a href="javascript:void(0)" class="btn btn-primary float-end" data-bs-toggle="modal"
                            data-bs-target="#add_pathology_test_modal">
                            Request Laboratory Test
                        </a>
                        <livewire:pathology-tests-table opdId="{{ $opdPatientDepartment->id }}" />
                    </div>
                    <div class="tab-pane fade opdRadiology" id="opdRadiology" role="tabpanel"
                        aria-labelledby="cases-tab">

                        <a href="javascript:void(0)" class="btn btn-primary float-end" data-bs-toggle="modal"
                        data-bs-target="#add_radiology_test_modal">
                            Request Radiology Test
                        </a>
                        <livewire:radiology-tests-table opdId="{{ $opdPatientDepartment->id }}" />
                    </div>
                    <div class="tab-pane fade opdTreatment" id="opdTreatment" role="tabpanel"
                        aria-labelledby="opdTreatment-tab">
                        <a href="javascript:void(0)" class="btn btn-primary float-end" data-bs-toggle="modal"
                            data-bs-target="#add_treatment_modal">
                            Add Treatment
                        </a>
                        <livewire:treatment-table patientId="{{$opdPatientDepartment->patient->id}}" opdId="{{ $opdPatientDepartment->id }}" />
                    </div>
                    <div class="tab-pane fade opdNotes" id="opdNotes" role="tabpanel"
                        aria-labelledby="opdNotes-tab">
                        <a href="javascript:void(0)" class="btn btn-primary float-end" data-bs-toggle="modal"
                            data-bs-target="#add_note_modal">
                            Add Notes
                        </a>
                        <livewire:notes-table patientId="{{$opdPatientDepartment->patient->id}}" opdDiagnosisId="{{ $opdPatientDepartment->id }}" />
                    </div>
                </div>

            </div>
            <div class="tab-pane fade opdDiagnosisNursingProgressReports" id="opdDiagnosisNursingProgressReports"
                role="tabpanel" aria-labelledby="cases-tab">
                <a href="javascript:void(0)" class="btn btn-primary float-end" data-bs-toggle="modal"
                    data-bs-target="#add_nursing_note_modal">
                    Add Nursing Progress Notes
                </a>
                <livewire:nursing-progress-notes-table opdId="{{ $opdPatientDepartment->id }}" />
            </div>

            <div class="tab-pane fade" id="opdPatientsTimelines" role="tabpanel"
                aria-labelledby="opdPatientsTimelinesTab">
                <div id="opdPatientTimelines"></div>
            </div>

            <div class="tab-pane fade" id="opdPatientsVitals" role="tabpanel"
                aria-labelledby="opdPatientsVitalsTab">
                <livewire:vitals-table opdId="{{ $opdPatientDepartment->id }}" />
            </div>
            
            @if($opdPatientDepartment->patient->patientUser->gender != 0)
                <div class="tab-pane fade" id="opdPatientsAntenatals" role="tabpanel"
                    aria-labelledby="opdPatientsAntenatalsTab">
                    <livewire:opd-antenatals-table opdId="{{ $opdPatientDepartment->id }}" patientId="{{ $opdPatientDepartment->patient_id }}"/>
                </div>
                <div class="tab-pane fade" id="opdPatientsPostnatals" role="tabpanel"
                    aria-labelledby="opdPatientsPostnatalsTab">
                    <livewire:opd-postnatals-table opdId="{{ $opdPatientDepartment->id }}" patientId="{{ $opdPatientDepartment->patient_id }}"/>
                </div>
                <div class="tab-pane fade" id="opdPatientsObstetric" role="tabpanel"
                    aria-labelledby="opdObstetricTab">
                    <livewire:opd-obstetric-history-table opdId="{{ $opdPatientDepartment->id }}" patientId="{{ $opdPatientDepartment->patient_id }}"/>
                </div>
            @endif
            <div class="tab-pane fade" id="showPatientPrescriptions" role="tabpanel">
                <livewire:patient-prescription-detail-table patientId="{{ $opdPatientDepartment->patient_id }}" />
            </div>
        </div>
    </div>
</div>
