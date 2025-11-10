@include('ipd_patient_departments.vitals-indicator')
<div>
    <div class="mt-7 overflow-hidden">
        <ul class="nav nav-tabs mb-5 pb-1 overflow-auto flex-nowrap justify-content-between text-nowrap" id="myTab"
            role="tablist">
            <li class="nav-item position-relative me-7 mb-3" role="presentation">
                <button class="nav-link active p-0" id="ipdOverview" data-bs-toggle="tab" data-bs-target="#poverview"
                    type="button" role="tab" aria-controls="overview" aria-selected="true">
                    <i class="fas fa-chart-pie"></i>
                    {{ __('messages.overview') }}
                </button>
            </li>
            <li class="nav-item position-relative me-7 mb-3" role="presentation">
                <button class="nav-link p-0" id="cases-tab" data-bs-toggle="tab" data-bs-target="#ipdDiagnosis"
                    type="button" role="tab" aria-controls="cases" aria-selected="false">
                    <i class="fa fa-stethoscope me-2"></i>
                    {{ __('messages.ipd_diagnosis') }}
                </button>
            </li>
            <li class="nav-item position-relative me-7 mb-3" role="presentation">
                <button class="nav-link p-0" id="ipdDiagnosisNursingProgressReports-tab" data-bs-toggle="tab"
                    data-bs-target="#ipdDiagnosisNursingProgressReports" type="button" role="tab"
                    aria-controls="NursingProgressReports" aria-selected="true">
                    <i class="fas fa-notes-medical"></i>
                    Nurses Notes
                </button>
            </li>

            <li class="nav-item position-relative me-7 mb-3" role="presentation">
                <button class="nav-link p-0 ipdConsultantInstruction" id="patients-tab" data-bs-toggle="tab"
                    data-bs-target="#ipdConsultantInstruction" type="button" role="tab" aria-controls="patients"
                    aria-selected="false">
                    <i class="fa fa-info-circle me-2"></i>
                    {{ __('messages.ipd_consultant_register') }}
                </button>
            </li>
            <li class="nav-item position-relative me-7 mb-3" role="presentation">
                <button class="nav-link p-0 ipdOperation" id="patients-tab" data-bs-toggle="tab"
                    data-bs-target="#ipdOperation" type="button" role="tab" aria-controls="patients"
                    aria-selected="false">
                    <i class="fa fa-sitemap me-2"></i>
                    {{ __('messages.operations') }}
                </button>
            </li>
            <li class="nav-item position-relative me-7 mb-3" role="presentation">
                <button class="nav-link p-0 ipdCharges" id="patients-tab" data-bs-toggle="tab"
                    data-bs-target="#ipdCharges" type="button" role="tab" aria-controls="patients"
                    aria-selected="false">
                    <i class="fa fa-money-bill-wave me-2"></i>
                    {{ __('messages.ipd_charges') }}
                </button>
            </li>
            <li class="nav-item position-relative me-7 mb-3" role="presentation">
                <button class="nav-link p-0" id="patients-tab" data-bs-toggle="tab" data-bs-target="#ipdTimelines"
                    type="button" role="tab" aria-controls="patients" aria-selected="false">
                    <i class="fa fa-clock me-2"></i>
                    {{ __('messages.ipd_timelines') }}
                </button>
            </li>
            <li class="nav-item position-relative me-7 mb-3" role="presentation">
                <button class="nav-link p-0 ipdVitals" id="patients-tab" data-bs-toggle="tab"
                    data-bs-target="#ipdVitals" type="button" role="tab" aria-controls="patients"
                    aria-selected="false">
                    <i class="fa fa-heartbeat me-2"></i>
                    {{ __('messages.vitals') }}
                </button>
            </li>
{{--            @if($ipdPatientDepartment->patient->patientUser->gender != 0)--}}
{{--                <li class="nav-item position-relative me-7 mb-3" role="presentation">--}}
{{--                    <button class="nav-link p-0 ipdAntenatal" id="patients-tab" data-bs-toggle="tab"--}}
{{--                        data-bs-target="#ipdAntenatal" type="button" role="tab" aria-controls="patients"--}}
{{--                        aria-selected="false">--}}
{{--                        <i class="fas fa-female"></i>--}}
{{--                        --}}{{-- {{ $antenatalStatus}} --}}
{{--                        {{ __('messages.antenatal.title') }}--}}
{{--                    </button>--}}
{{--                </li>--}}
{{--                <li class="nav-item position-relative me-7 mb-3" role="presentation">--}}
{{--                    <button class="nav-link p-0 ipdPostnatal" id="postnatals-tab" data-bs-toggle="tab"--}}
{{--                    data-bs-target="#ipdPostnatal" type="button" role="tab" aria-controls="postnatals"--}}
{{--                    aria-selected="false">--}}
{{--                        <i class="fas fa-baby"></i>--}}
{{--                        {{ __('messages.postnatal.postnatal_history') }}--}}
{{--                    </button>--}}
{{--                </li>--}}
{{--                    --}}
{{--                <li class="nav-item position-relative me-7 mb-3" role="presentation">--}}
{{--                    <button class="nav-link p-0 ipdPreviousObstetricHistory" id="postnatals-tab" data-bs-toggle="tab"--}}
{{--                    data-bs-target="#ipdPreviousObstetricHistory" type="button" role="tab" aria-controls="postnatals"--}}
{{--                    aria-selected="false">--}}
{{--                        <i class="fa fa-calendar-check me-2"></i>--}}
{{--                        {{ __('messages.previous_obstetric_history.previous_obstetric_history') }}--}}
{{--                    </button>--}}
{{--                </li>--}}
{{--            @endif--}}

            <li class="nav-item position-relative me-7 mb-3" role="presentation">
                <button class="nav-link p-0 ipdPayment" id="patients-tab" data-bs-toggle="tab"
                    data-bs-target="#ipdPayment" type="button" role="tab" aria-controls="patients"
                    aria-selected="false">
                    <i class="fa fa-credit-card me-2"></i>
                    {{ __('messages.account.payments') }}
                </button>
            </li>
            <li class="nav-item position-relative me-7 mb-3">
                <a class="nav-link p-0" data-bs-toggle="tab"
                    href="#showPatientPrescriptions"><i class="fas fa-prescription-bottle-alt"></i> {{ __('messages.prescriptions') }}</a>
            </li>
            <li class="nav-item position-relative me-7 mb-3" role="presentation">
                <button class="nav-link p-0" id="patients-tab" data-bs-toggle="tab" data-bs-target="#ipdBill"
                    type="button" role="tab" aria-controls="patients" aria-selected="false">
                    <i class="fa fa-file-invoice-dollar me-2"></i>
                    {{ __('messages.bills') }}
                </button>
            </li>

        </ul>

        <div class="tab-content" id="myTabContent">
            <div class="tab-pane fade show active" id="poverview" role="tabpanel" aria-labelledby="overview-tab">
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
                                            <img src="{{ $ipdPatientDepartment->patient->patientUser->image_url }}"
                                                alt="image" />
                                        </div>
                                    </div>
                                    <div class="col-lg-9">
                                        <table class="table  mb-0">
                                            <tbody>
                                                <tr>
                                                    <td>{{ __('messages.user.gender') }}</td>
                                                    <td>{{ $ipdPatientDepartment->patient->patientUser->gender == 0 ? 'Male' : 'Female' }}
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>{{ __('messages.user.email') }}</td>
                                                    <td class="text-break w-75">
                                                        {{ $ipdPatientDepartment->patient->patientUser->email }}
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>{{ __('messages.user.phone') }}</td>
                                                    <td>{{ $ipdPatientDepartment->patient->patientUser->phone ?? __('messages.common.n/a') }}
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
                                                    <td>{{ !empty($ipdPatientDepartment->patientCase) ? $ipdPatientDepartment->patientCase->case_id : __('messages.common.n/a') }}
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>{{ __('messages.ipd_patient.ipd_number') }}</td>
                                                    <td>{{ $ipdPatientDepartment->ipd_number }}</td>
                                                </tr>
                                                <tr>
                                                    <td class="white-space-nowrap" width="40%">
                                                        {{ __('messages.ipd_patient.admission_date') }}</td>
                                                    <td>{{ \Carbon\Carbon::parse($ipdPatientDepartment->admission_date)->translatedFormat('jS M, Y') }}
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>{{ __('messages.ipd_patient.bed_id') }}</td>
                                                    <td>{{ $ipdPatientDepartment->bed->name ?? '' }}</td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>

                                </div>
                                <hr>
                                <div class="row">
                                    <p><i class="fa fa-tag"></i> {{ __('messages.ipd_patient.symptoms') }}</p>
                                    <ul class="timeline-ps-46 mb-0">
                                        <li>
                                            <div>
                                                {!! !empty($ipdPatientDepartment->symptoms)
                                                    ? nl2br(e($ipdPatientDepartment->symptoms))
                                                    : __('messages.common.n/a') !!}
                                            </div>
                                        </li>
                                    </ul>
                                </div>
                                <hr>
                                <div class="row mb-2">
                                    <div class="col-lg-10">
                                        <h3 class="text-uppercase fs-5">
                                            {{ __('messages.ipd_patient_consultant_register.consultant_doctor') }}</h3>
                                    </div>
                                    <div class="col-lg-2 text-end">
                                        <a href="javascript:void(0)" data-bs-toggle="modal"
                                            data-bs-target="#addConsultantInstructionModal">
                                            <i class="fa fa-plus text-dark"></i>
                                        </a>
                                    </div>
                                </div>
                                <div id="consultant-div">
                                    @if (count($consultantDoctor) == 0)
                                        <tr class="text-center">
                                            <td colspan="4">
                                                <div class="mb-5">
                                                    {{ __('messages.common.no') . ' ' . __('messages.ipd_consultant_doctor') }}
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
                                </div>

                                <div class="row" id="overviewIpdTimeline">
                                    <div class="mb-5">
                                        <h3 class="text-uppercase fs-5">
                                            {{ __('messages.ipd_patient_timeline.timeline') }}</h3>
                                    </div>
                                    @forelse($ipdTimeline as $timeline)
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
                                                <div class="t-table border-top-0 mb-5 ipd-timeline-desc">
                                                    {{ $timeline->description ?? __('messages.common.n/a') }}
                                                </div>
                                            </div>
                                        </div>
                                    @empty
                                        <div class="mb-5">
                                            {{ __('messages.ipd_patient_timeline.no_timeline_found') }}</div>
                                    @endforelse
                                    @if (count($ipdTimeline) != 0)
                                        <div class="col-1 pe-0 ps-5  d-flex justify-content-center">
                                            <div class="list-icon bg-light">
                                                <i class="fa fa-clock text-primary"></i>
                                            </div>
                                        </div>
                                    @endif
                                </div>

                                <div class="mb-10 mt-5">
                                    <div class="d-flex justify-content-between">
                                        <h3 class="text-uppercase fs-5 mb-4">{{ __('messages.operation.operation') }}
                                        </h3>
                                        @if (App\Models\IpdOperation::where('ipd_patient_department_id', $ipdPatientDepartment->id)->count() > 5)
                                            <ul class="nav mb-4" id="myTab" role="tablist">
                                                <li class="nav-item">
                                                    <a class="nav-link ipd-operation-btn btn btn-primary btn-sm text-capitalize text-white"
                                                        data-bs-toggle="tab" data-bs-target="#ipdOperation"
                                                        id="cases-tab" type="button" role="tab"
                                                        aria-controls="cases"
                                                        aria-selected="false">{{ __('messages.common.view') }}</a>
                                                </li>
                                            </ul>
                                        @endif
                                    </div>
                                    <livewire:overview-ipd-operation-table
                                        ipdOperationId="{{ $ipdPatientDepartment->id }}" />
                                </div>
                            </div>
                            <div class="col-6">
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
                            </div>
                            <div class="mb-10">
                                <div class="d-flex justify-content-between">
                                    <h3 class="text-uppercase fs-5">{{ __('messages.prescription.prescription') }}
                                    </h3>
                                    @if (App\Models\IpdPrescription::where('ipd_patient_department_id', $ipdPatientDepartment->id)->count() > 5)
                                        <ul class="nav" id="myTab" role="tablist">
                                            <li class="nav-item">
                                                <a class="nav-link ipd-prescription-btn btn btn-primary btn-sm text-capitalize text-white"
                                                    data-bs-toggle="tab" data-bs-target="#ipdPrescriptions"
                                                    id="cases-tab" type="button" role="tab"
                                                    aria-controls="cases" aria-selected="false">view all</a>
                                            </li>
                                        </ul>
                                    @endif
                                </div>
                                @if (App\Models\IpdPrescription::where('ipd_patient_department_id', $ipdPatientDepartment->id)->count() > 0)
                                    <livewire:overview-ipd-prescription-table
                                        ipdPrescriptionId="{{ $ipdPatientDepartment->id }}" />
                                @else
                                    <table class="table table-striped">
                                        <thead class="">
                                            <tr>
                                                <th scope="col" class="">
                                                    {{ __('messages.ipd_patient.ipd_number') }}
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
                            </div>
                            <div class="mb-10">
                                <div class="d-flex justify-content-between">
                                    <h3 class="text-uppercase fs-5">
                                        {{ __('messages.ipd_patient_consultant_register.consultant_instruction') }}
                                    </h3>
                                    @if (App\Models\IpdConsultantRegister::where('ipd_patient_department_id', $ipdPatientDepartment->id)->count() > 5)
                                        <ul class="nav" id="myTab" role="tablist">
                                            <li class="nav-item">
                                                <a class="nav-link ipd-consultant-btn btn btn-primary btn-sm text-capitalize text-white"
                                                    data-bs-toggle="tab" data-bs-target="#ipdConsultantInstruction"
                                                    id="cases-tab" type="button" role="tab"
                                                    aria-controls="cases" aria-selected="false">view all</a>
                                            </li>
                                        </ul>
                                    @endif
                                </div>
                                @if (App\Models\IpdConsultantRegister::where('ipd_patient_department_id', $ipdPatientDepartment->id)->count() > 0)
                                    <livewire:overview-ipd-consultant-table
                                        ipdConsultantId="{{ $ipdPatientDepartment->id }}" />
                                @else
                                    <table class="table table-striped">
                                        <thead class="">
                                            <tr>
                                                <th scope="col" class="">
                                                    {{ __('messages.investigation_report.doctor') }}
                                                </th>

                                                <th scope="col" class="">
                                                    {{ __('messages.ipd_patient_consultant_register.applied_date') }}
                                                </th>

                                                <th scope="col" class="">
                                                    {{ __('messages.ipd_patient_consultant_register.instruction_date') }}
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
                                    @if (App\Models\IpdCharge::where('ipd_patient_department_id', $ipdPatientDepartment->id)->count() > 5)
                                        <ul class="nav" id="myTab" role="tablist">
                                            <li class="nav-item">
                                                <a class="nav-link ipd-charges-btn btn btn-primary btn-sm text-capitalize text-white"
                                                    data-bs-toggle="tab" data-bs-target="#ipdCharges" type="button"
                                                    role="tab" aria-controls="cases" aria-selected="false">view
                                                    all</a>
                                            </li>
                                        </ul>
                                    @endif
                                </div>
                                @if (App\Models\IpdCharge::where('ipd_patient_department_id', $ipdPatientDepartment->id)->count() > 0)
                                    <livewire:overview-ipd-charges-table
                                        ipdChargeId="{{ $ipdPatientDepartment->id }}" />
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
                                                    {{ __('messages.ipd_patient_charges.applied_charge') }}
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
                                    @if (App\Models\IpdPayment::whereIpdPatientDepartmentId($ipdPatientDepartment->id)->count() > 5)
                                        <ul class="nav" id="myTab" role="tablist">
                                            <li class="nav-item">
                                                <a class="nav-link ipd-payment-btn btn btn-primary btn-sm text-capitalize text-white"
                                                    data-bs-toggle="tab" data-bs-target="#ipdPayment" type="button"
                                                    role="tab" aria-controls="cases" aria-selected="false">view
                                                    all</a>
                                            </li>
                                        </ul>
                                    @endif
                                </div>
                                @if (App\Models\IpdPayment::whereIpdPatientDepartmentId($ipdPatientDepartment->id)->count() > 0)
                                    <livewire:overview-ipd-payment-table
                                        ipdPaymentId="{{ $ipdPatientDepartment->id }}" />
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
                                                    {{ __('messages.ipd_payments.payment_mode') }}
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
                            </div>
                            <div class="mb-10">
                                <div class="d-flex justify-content-between">
                                    <h3 class="text-uppercase fs-5">

                                        {{ __('messages.patient_diagnosis_test.diagnosis') }}</h3>
                                    @if (App\Models\IpdDiagnosis::whereIpdPatientDepartmentId($ipdPatientDepartment->id)->count() > 5)
                                        <ul class="nav" id="myTab" role="tablist">
                                            <li class="nav-item">
                                                <a class="nav-link ipd-diagnosis-btn btn btn-primary btn-sm text-capitalize text-white"
                                                    data-bs-toggle="tab" data-bs-target="#ipdDiagnosis"
                                                    id="cases-tab" type="button" role="tab"
                                                    aria-controls="cases" aria-selected="false">view
                                                    all</a>
                                            </li>
                                        </ul>
                                    @endif
                                </div>
                                @if (App\Models\IpdDiagnosis::whereIpdPatientDepartmentId($ipdPatientDepartment->id)->count() > 0)
                                    <livewire:overview-ipd-diagnosis-table
                                        ipdDiagnosisId="{{ $ipdPatientDepartment->id }}" />
                                @else
                                    <table class="table table-striped">
                                        <thead class="">
                                            <tr>
                                                <th scope="col" class="">
                                                    {{ __('messages.ipd_patient_diagnosis.report_type') }}
                                                </th>

                                                <th scope="col" class="">
                                                    {{ __('messages.ipd_patient_diagnosis.report_date') }}
                                                </th>

                                                <th scope="col" class="">
                                                    {{ __('messages.document.document') }}
                                                </th>

                                                <th scope="col" class="">
                                                    {{ __('messages.ipd_patient_diagnosis.description') }}
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
        </div>

        <div class="tab-pane fade ipdDiagnosis" id="ipdDiagnosis" role="tabpanel" aria-labelledby="cases-tab">

            <ul class="nav nav-tabs mb-5 pb-1 overflow-auto flex-nowrap text-nowrap" id="myDiagnosticsTab"
                role="tablist">
                <li class="nav-item position-relative me-7 mb-3" role="presentation">

                    <button class="nav-link active p-0" id="ipdDiagnosisComplaints-tab" data-bs-toggle="tab"
                        data-bs-target="#ipdDiagnosisComplaints" type="button" role="tab"
                        aria-controls="Complaints" aria-selected="true">
                        <i class="fa fa-comments"></i>
                        Complaints
                    </button>
                </li>

                <li class="nav-item position-relative me-7 mb-3" role="presentation">

                    <button class="nav-link p-0" id="ipdDiagnosisGeneralExamination-tab" data-bs-toggle="tab"
                        data-bs-target="#ipdDiagnosisGeneralExamination" type="button" role="tab"
                        aria-controls="GeneralExamination" aria-selected="true">
                        <i class="fa fa-search"></i>
                        Examination
                    </button>
                </li>

                {{-- <li class="nav-item position-relative me-7 mb-3" role="presentation">
                    <button class="nav-link p-0" id="ipdDiagnosisSystemicExamination-tab" data-bs-toggle="tab"
                        data-bs-target="#ipdDiagnosisSystemicExamination" type="button" role="tab"
                        aria-controls="SystemicExamination" aria-selected="true">
                        <i class="fa fa-heartbeat"></i>
                        Systemic Examination
                    </button>
                </li> --}}


                <li class="nav-item position-relative me-7 mb-3" role="presentation">
                    <button class="nav-link p-0" id="ipdProvisionalDiagnosis-tab" data-bs-toggle="tab"
                        data-bs-target="#ipdProvisionalDiagnosis" type="button" role="tab"
                        aria-controls="Diagnosis" aria-selected="true">
                        <i class="fa fa-stethoscope"></i>
                       Provisional Diagnosis
                    </button>
                </li>

                <li class="nav-item position-relative me-7 mb-3" role="presentation">
                    <button class="nav-link p-0" id="ipdDiagnosisDiagnosis-tab" data-bs-toggle="tab"
                        data-bs-target="#ipdDiagnosisDiagnosis" type="button" role="tab"
                        aria-controls="Diagnosis" aria-selected="true">
                        <i class="fa fa-stethoscope"></i>
                        Diagnosis
                    </button>
                </li>

                @role('Admin|Doctor|Receptionist|Nurse')
                    <li class="nav-item position-relative me-7 mb-3" role="presentation">
                        <button class="nav-link p-0 ipdPrescriptions" id="patients-tab" data-bs-toggle="tab"
                            data-bs-target="#ipdPrescriptions" type="button" role="tab" aria-controls="patients"
                            aria-selected="false">
                            <i class="fa fa-prescription-bottle-alt"></i>
                            {{ __('messages.ipd_prescription') }}
                        </button>
                    </li>
                @endrole

                <li class="nav-item position-relative me-7 mb-3" role="presentation">
                    <button class="nav-link p-0" id="cases-tab" data-bs-toggle="tab" data-bs-target="#ipdPathology"
                        type="button" role="tab" aria-controls="cases" aria-selected="false">
                        <i class="fa fa-vial"></i>
                        Pathology
                    </button>
                </li>
                <li class="nav-item position-relative me-7 mb-3" role="presentation">
                    <button class="nav-link p-0" id="cases-tab" data-bs-toggle="tab" data-bs-target="#ipdRadiology"
                        type="button" role="tab" aria-controls="cases" aria-selected="false" style="color: #007bff; font-weight: bold;">
                        <i class="fa fa-x-ray"></i>
                        Radiology
                    </button>
                </li>

                <li class="nav-item position-relative me-7 mb-3" role="presentation">
                    <button class="nav-link p-0" id="cases-tab" data-bs-toggle="tab" data-bs-target="#ipdTreatment"
                        type="button" role="tab" aria-controls="cases" aria-selected="false">
                       <i class="fa fa-ambulance"></i>
                        Treatment
                    </button>
                </li>

                <li class="nav-item position-relative me-7 mb-3" role="presentation">
                    <button class="nav-link p-0" id="cases-tab" data-bs-toggle="tab" data-bs-target="#ipdNotes"
                        type="button" role="tab" aria-controls="cases" aria-selected="false">
                        <i class="fa fa-file-alt"></i>
                        Notes
                    </button>
                </li>

            </ul>

            <div class="tab-content" id="myDiagnosticsTabContent">
                <div class="tab-pane fade show active ipdDiagnosisComplaints" id="ipdDiagnosisComplaints"
                    role="tabpanel" aria-labelledby="ipdDiagnosisComplaints-tab">
                    <a href="javascript:void(0)" class="btn btn-primary float-end" data-bs-toggle="modal"
                        data-bs-target="#add_complaint_modal">
                        Add Complaint
                    </a>
                    <livewire:complaints-table ipdId="{{ $ipdPatientDepartment->id }}" />
                </div>
                <div class="tab-pane fade ipdDiagnosisGeneralExamination" id="ipdDiagnosisGeneralExamination"
                    role="tabpanel" aria-labelledby="ipdDiagnosisGeneralExamination-tab">
                    <a href="javascript:void(0)" class="btn btn-primary float-end" data-bs-toggle="modal"
                        data-bs-target="#add_general_examination_modal">
                        Add Examination
                    </a>
                    <livewire:general-examination-table ipdId="{{ $ipdPatientDepartment->id }}" />
                </div>
                <div class="tab-pane fade ipdDiagnosisSystemicExamination" id="ipdDiagnosisSystemicExamination"
                    role="tabpanel" aria-labelledby="ipdDiagnosisSystemicExamination-tab">
                    <a href="javascript:void(0)" class="btn btn-primary float-end" data-bs-toggle="modal"
                        data-bs-target="#add_systemic_examination_modal">
                        Add Systemic Examination
                    </a>
                    <livewire:systemic-examination-table ipdId="{{ $ipdPatientDepartment->id }}" />
                </div>

                <div class="tab-pane fade ipdDiagnosisDiagnosis" id="ipdDiagnosisDiagnosis" role="tabpanel"
                    aria-labelledby="ipdDiagnosisDiagnosis-tab">
                    <livewire:ipd-diagnosis-table ipdDiagnosisId="{{ $ipdPatientDepartment->id }}" />
                </div>

                <div class="tab-pane fade ipdProvisionalDiagnosis" id="ipdProvisionalDiagnosis" role="tabpanel"
                    aria-labelledby="ipdProvisionalDiagnosis-tab">
                    <livewire:ipd-provisional-diagnosis-table ipdProvisionalDiagnosisId="{{ $ipdPatientDepartment->id }}" />
                </div>


                <div class="tab-pane fade ipdPrescriptions" id="ipdPrescriptions" role="tabpanel"
                    aria-labelledby="cases-tab">
                    <a href="javascript:void(0)" class="btn btn-primary float-end" data-bs-toggle="modal"
                        data-bs-target="#addIpdPrescriptionModal">
                        {{ __('messages.ipd_patient_prescription.new_prescription') }}
                    </a>
                    <livewire:ipd-prescription-table ipdPrescriptionId="{{ $ipdPatientDepartment->id }}" />
                </div>

                <div class="tab-pane fade ipdPathology" id="ipdPathology" role="tabpanel"
                    aria-labelledby="cases-tab">
                    <livewire:pathology-tests-table ipdId="{{ $ipdPatientDepartment->id }}" />
                </div>
                <div class="tab-pane fade ipdRadiology" id="ipdRadiology" role="tabpanel"
                    aria-labelledby="cases-tab">
                    <livewire:radiology-tests-table ipdId="{{ $ipdPatientDepartment->id }}" />
                </div>
                <div class="tab-pane fade ipdTreatment" id="ipdTreatment" role="tabpanel"
                    aria-labelledby="ipdTreatment-tab">
                    <a href="javascript:void(0)" class="btn btn-primary float-end" data-bs-toggle="modal"
                        data-bs-target="#add_treatment_modal">
                        Add Treatment
                    </a>
                    <livewire:treatment-table patientId="{{$ipdPatientDepartment->patient->id}}" ipdId="{{ $ipdPatientDepartment->id }}" />
                </div>
                <div class="tab-pane fade ipdNotes" id="ipdNotes" role="tabpanel" aria-labelledby="ipdNotes-tab">
                    <a href="javascript:void(0)" class="btn btn-primary float-end" data-bs-toggle="modal"
                        data-bs-target="#add_note_modal">
                        Add Notes
                    </a>
                    <livewire:notes-table patientId="{{$ipdPatientDepartment->patient->id}}" ipdDiagnosisId="{{ $ipdPatientDepartment->id }}" />
                </div>

            </div>

        </div>

        <div class="tab-pane fade ipdDiagnosisNursingProgressReports" id="ipdDiagnosisNursingProgressReports"
            role="tabpanel" aria-labelledby="cases-tab">
            <a href="javascript:void(0)" class="btn btn-primary float-end" data-bs-toggle="modal"
                data-bs-target="#add_nursing_note_modal">
                Add Nursing Progress Notes
            </a>
            <livewire:nursing-progress-notes-table ipdId="{{ $ipdPatientDepartment->id }}" />
        </div>

        <div class="tab-pane fade ipdConsultantInstruction" id="ipdConsultantInstruction" role="tabpanel"
            aria-labelledby="cases-tab">
            <livewire:ipd-consultant-register-table ipdDiagnosisId="{{ $ipdPatientDepartment->id }}" />
        </div>
        <div class="tab-pane fade ipdOperation" id="ipdOperation" role="tabpanel" aria-labelledby="cases-tab">
            <livewire:ipd-operation-table ipdOperationId="{{ $ipdPatientDepartment->id }}" />
        </div>
        <div class="tab-pane fade" id="ipdCharges" role="tabpanel" aria-labelledby="cases-tab">
            @if (!$ipdPatientDepartment->bill_status)
                <div class="card-title">
                    <a href="javascript:void(0)" class="btn btn-primary float-end" data-bs-toggle="modal"
                        data-bs-target="#addIpdChargesModal">
                        {{ __('messages.ipd_patient_charges.new_charge') }}
                    </a>
                </div>
            @endif
            <livewire:ipd-charge-table ipdDiagnosisId="{{ $ipdPatientDepartment->id }}" />
        </div>

        <div class="tab-pane fade" id="ipdTimelines" role="tabpanel" aria-labelledby="cases-tab">
            <div id="ipdTimelines"></div>
        </div>
        <div class="tab-pane fade ipdVitals" id="ipdVitals" role="tabpanel" aria-labelledby="cases-tab">
            <livewire:vitals-table ipdId="{{ $ipdPatientDepartment->id }}" />
        </div>
        <div class="tab-pane fade ipdPreviousObstetricHistory" id="ipdPreviousObstetricHistory" role="tabpanel" aria-labelledby="cases-tab">
            <livewire:ipd-obstetric-history-table ipdId="{{ $ipdPatientDepartment->id }}" />
        </div>
        <div class="tab-pane fade ipdPostnatal" id="ipdPostnatal" role="tabpanel" aria-labelledby="cases-tab">
            <livewire:ipd-postnatal-table ipdId="{{ $ipdPatientDepartment->id }}" />
        </div>
        <div class="tab-pane fade ipdAntenatal" id="ipdAntenatal" role="tabpanel" aria-labelledby="cases-tab">
            <livewire:antenatal-table ipdId="{{ $ipdPatientDepartment->id }}" />
        </div>

        <div class="tab-pane fade" id="ipdPayment" role="tabpanel" aria-labelledby="cases-tab">
            @if ($ipdPatientDepartment->bill)
                @if ($ipdPatientDepartment->bill->net_payable_amount > 0)
                    <a href="javascript:void(0)" class="btn btn-primary float-end" data-bs-toggle="modal"
                        data-bs-target="#addIpdPaymentModal">
                        {{ __('messages.payment.new_payment') }}
                    </a>
                @endif
            @else
                <a href="javascript:void(0)" class="btn btn-primary float-end" data-bs-toggle="modal"
                    data-bs-target="#addIpdPaymentModal">
                    {{ __('messages.payment.new_payment') }}
                </a>
            @endif
            <livewire:ipd-payment-table ipdPatientDepartmentId="{{ $ipdPatientDepartment->id }}" />
        </div>
        <div class="tab-pane fade" id="showPatientPrescriptions" role="tabpanel">
            <livewire:patient-prescription-detail-table patientId="{{ $ipdPatientDepartment->patient_id }}" />
        </div>
        <div class="tab-pane fade" id="ipdBill" role="tabpanel" aria-labelledby="cases-tab">
            <div class="table-responsive viewList overflow-hidden">
                <div class="card">
                    <div class="card-body">
                        @include('ipd_bills.table')
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
