@include('patients.vitals-indicator')
<div>
    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-xxl-5 col-12">
                    <div class="d-sm-flex align-items-center mb-5 mb-xxl-0 text-center text-sm-start">
                        <div class="image image-circle image-small">
                            <img src="{{ !empty($data->patientUser->image_url) ? $data->patientUser->image_url : '' }}"
                                alt="image" />
                        </div>
                        <div class="ms-0 ms-md-10 mt-5 mt-sm-0">
                            <h2><a href="javascript:void(0)"
                                    class="text-decoration-none">{{ !empty($data->patientUser->full_name) ? $data->patientUser->full_name : '' }}</a>
                            </h2>

                            <span class="text-gray-600 text-decoration-none fs-5">
                                {{ !empty($data->patient_unique_id) ? $data->patient_unique_id : '' }}
                            </span>
                            {{-- <a href="mailto:{{ !empty($data->patientUser->email) ? $data->patientUser->email : '' }}"
                                class="text-gray-600 text-decoration-none fs-5">
                                {{ !empty($data->patientUser->email) ? $data->patientUser->email : '' }}
                            </a> --}}
                            <span class="d-flex align-items-center me-2 mb-2 mt-2">
                                @if (
                                    !empty($data->address->address1) ||
                                        !empty($data->address->address2) ||
                                        !empty($data->address->city) ||
                                        !empty($data->address->zip))
                                    <span><i class="fas fa-location"></i></span>
                                @endif
                                <span class="p-2">
                                    {{ !empty($data->address->address1) ? $data->address->address1 : '' }}{{ !empty($data->address->address2) ? (!empty($data->address->address1) ? ',' : '') : '' }}
                                    {{ empty($data->address->address1) || !empty($data->address->address2) ? (!empty($data->address->address2) ? $data->address->address2 : '') : '' }}
                                    {{ empty($data->address->address1) && empty($data->address->address2) ? '' : '' }}{{ !empty($data->address->city) ? ',' . $data->address->city : '' }}{{ !empty($data->address->zip) ? ',' . $data->address->zip : '' }}
                                </span>
                            </span>
                        </div>
                    </div>
                </div>

                <div class="col-xxl-7 col-12">
                    <div class="row justify-content-center">
                        <div class="col-md-4 col-sm-6 col-12 mb-6 mb-md-0">
                            <div class="border rounded-10 p-5 h-100">
                                <h2 class="text-primary mb-3">{{ !empty($data->cases) ? $data->cases->count() : 0 }}
                                </h2>
                                <h3 class="fs-5 fw-light text-gray-600 mb-0">{{ __('messages.patient.total_cases') }}
                                </h3>
                            </div>
                        </div>
                        <div class="col-md-4 col-sm-6 col-12 mb-6 mb-md-0">
                            <div class="border rounded-10 p-5 h-100">
                                <h2 class="text-primary mb-3">
                                    {{ !empty($data->admissions) ? $data->admissions->count() : 0 }}</h2>
                                <h3 class="fs-5 fw-light text-gray-600 mb-0">
                                    {{ __('messages.patient.total_admissions') }}</h3>
                            </div>
                        </div>
                        <div class="col-md-4 col-sm-6 col-12 mb-6 mb-md-0">
                            <div class="border rounded-10 p-5 h-100">
                                <h2 class="text-primary mb-3">
                                    {{ !empty($data->appointments) ? $data->appointments->count() : 0 }}</h2>
                                <h3 class="fs-5 fw-light text-gray-600 mb-0">
                                    {{ __('messages.patient.total_appointments') }}</h3>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="mt-7 overflow-hidden">
        <ul class="nav nav-tabs mb-5 pb-1 overflow-auto flex-nowrap text-nowrap">
            <li class="nav-item position-relative me-7 mb-3">
                <a class="nav-link active p-0" data-bs-toggle="tab"
                    href="#PatientOverview"><i class="fas fa-tachometer-alt"></i> {{ __('messages.overview') }}</a>
            </li>
            <li class="nav-item position-relative me-7 mb-3">
                <a class="nav-link p-0" data-bs-toggle="tab" href="#showPatientCases"><i class="fas fa-briefcase"></i> {{ __('messages.cases') }}</a>
            </li>
            <li class="nav-item position-relative me-7 mb-3">
                <a class="nav-link p-0" data-bs-toggle="tab"
                    href="#showPatientAdmissions"><i class="fas fa-user-plus"></i> {{ __('messages.admissions') }}</a>
            </li>
            <li class="nav-item position-relative me-7 mb-3">
                <a class="nav-link p-0" data-bs-toggle="tab"
                    href="#showPatientAppointments"><i class="fas fa-calendar-check"></i> {{ __('messages.appointments') }}</a>
            </li>
            <li class="nav-item position-relative me-7 mb-3">
                <a class="nav-link p-0" data-bs-toggle="tab"
                    href="#showPatientPrescriptions"><i class="fas fa-prescription-bottle-alt"></i> {{ __('messages.prescriptions') }}</a>
            </li>
            <li class="nav-item position-relative me-7 mb-3">
                <a class="nav-link p-0" data-bs-toggle="tab"
                    href="#showPatientPathology"><i class="fas fa-flask"></i> {{ __('messages.pathology_tests') }}</a>
            </li>
            <li class="nav-item position-relative me-7 mb-3">
                <a class="nav-link p-0" data-bs-toggle="tab"
                    href="#showPatientVitals"><i class="fas fa-heartbeat"></i> {{ __('messages.vitals') }}</a>
            </li>
            @if(!$data->company_id)
            <li class="nav-item position-relative me-7 mb-3">
                <a class="nav-link p-0" data-bs-toggle="tab" href="#showPatientBills"><i class="fas fa-file-invoice-dollar"></i> {{ __('messages.bills') }}</a>
            </li>
            @endif
            <li class="nav-item position-relative me-7 mb-3">
                <a class="nav-link p-0" data-bs-toggle="tab"
                    href="#showPatientInvoices"><i class="fas fa-file-invoice"></i> {{ __('messages.invoices') }}</a>
            </li>
            <li class="nav-item position-relative me-7 mb-3">
                <a class="nav-link p-0" data-bs-toggle="tab"
                    href="#showPatientAdvancedPayments"><i class="fas fa-hand-holding-usd"></i> {{ __('messages.adv_payments') }}</a>
            </li>
            <li class="nav-item position-relative me-7 mb-3">
                <a class="nav-link p-0" data-bs-toggle="tab"
                    href="#showPatientDocument"><i class="fas fa-folder-open"></i> {{ __('messages.docs') }}</a>
            </li>
            <li class="nav-item position-relative me-7 mb-3">
                <a class="nav-link p-0" data-bs-toggle="tab"
                    href="#showPatientVaccinated"><i class="fas fa-syringe"></i> {{ __('messages.vaccinations') }}</a>
            </li>

        </ul>
    </div>
</div>
<div class="tab-content" id="myPatientTabContent">
    <div class="tab-pane fade show active" id="PatientOverview" role="tabpanel">
    <div class="card d-flex justify-content-center me-2">
        <div class="card-body d-flex justify-content-center me-2">
                        <div class="card" style="width: 40rem; box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.2);">
                            {{-- <div class="col-sm-8">
                                <strong for="name" style="margin-left: 40px; margin-top:10px; font-size: 15px"
                                    class="text-gray-600">{{ __('messages.case.case_id') . ':' }}</strong>
                                    <em
                                        class="badge bg-light-info">{{ !empty($opdPatientDepartment->opd_number) ? $opdPatientDepartment->opd_number : __('messages.common.n/a') }}</em>
                            </div> --}}
                            @if(!is_null($data->staff_id))
                                <div class="col-sm-8">
                                    <strong for="name" style="margin-left: 40px; margin-top:10px; font-size: 15px"
                                        class="text-gray-600">{{ __('messages.case.staff_id') . ':' }}</strong>
                                        <em
                                            class="badge bg-light-info">{{  $data->staff_id }}</em>
                                </div>
                            @endif
                            <div class="card-body">
                                <h5 class="card-title text-primary mb-4">Personal Information</h5>

                                <div class="row">
                                    <!-- Full Name -->
                                    <div class="col-md-6 mb-3">
                                        <div class="p-2 bg-light rounded d-flex align-items-center">
                                            <i class="fas fa-user me-2 text-info"></i> <!-- Name Icon -->
                                            <span class="text-muted">Full Name</span>
                                            <div class="fw-bold ms-auto">{{ !empty($data->patientUser->first_name) && !empty($data->patientUser->last_name) ? $data->patientUser->first_name . ' ' . $data->patientUser->last_name : __('messages.common.n/a') }}</div>
                                        </div>
                                    </div>
                                                                        <!-- Email -->
                                    <div class="col-md-6 mb-3">
                                        <div class="p-2 bg-light rounded d-flex align-items-center">
                                            <i class="fas fa-envelope me-2 text-info"></i> <!-- Email Icon -->
                                            <span class="text-muted">Email</span>
                                            <div class="fw-bold ms-auto">{{ !empty($data->patientUser->email) ? $data->patientUser->email : __('messages.common.n/a') }}</div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <!-- Phone -->
                                    <div class="col-md-6 mb-3">
                                        <div class="p-2 bg-light rounded d-flex align-items-center">
                                            <i class="fas fa-phone me-2 text-info"></i> <!-- Phone Icon -->
                                            <span class="text-muted">Phone</span>
                                            <div class="fw-bold ms-auto">{{ !empty($data->patientUser->phone) ? $data->patientUser->phone : __('messages.common.n/a') }}</div>
                                        </div>
                                    </div>
                                    <!-- Age -->
                                    <div class="col-md-6 mb-3">
                                        <div class="p-2 bg-light rounded d-flex align-items-center">
                                            <i class="fas fa-hourglass-half me-2 text-info"></i>
                                            <span class="text-muted">Age</span>
                                            <div class="fw-bold ms-auto">{{ !empty($data->patientUser->age_new) ? $data->patientUser->age_new : __('messages.common.n/a') }}</div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <!-- Date of Birth -->
                                    <div class="col-md-6 mb-3">
                                        <div class="p-2 bg-light rounded d-flex align-items-center">
                                            <i class="fas fa-birthday-cake me-2 text-info"></i> <!-- DOB Icon -->
                                            <span class="text-muted">Date of Birth</span>
                                            <div class="fw-bold ms-auto">{{ !empty($data->patientUser->dob) ? $data->patientUser->dob : __('messages.common.n/a') }}</div>
                                        </div>
                                    </div>
                                    <!-- Blood Group -->
                                    <div class="col-md-6 mb-3">
                                        <div class="p-2 bg-light rounded d-flex align-items-center">
                                            <i class="fas fa-tint me-2 text-info"></i> <!-- Blood Group Icon -->
                                            <span class="text-muted">Blood Group</span>
                                            <div class="fw-bold ms-auto">{{ !empty($data->patientUser->blood_group) ? $data->patientUser->blood_group : __('messages.common.n/a') }}</div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <!-- Gender -->
                                    <div class="col-md-6 mb-3">
                                        <div class="p-2 bg-light rounded d-flex align-items-center">
                                            <i class="fas fa-venus-mars me-2 text-info"></i>
                                            <span class="text-muted">Gender</span>
                                            <div class="fw-bold ms-auto">
                                                {{ isset($data->patientUser->gender) ? ($data->patientUser->gender == 0 ? 'Male' : 'Female') : __('messages.common.n/a') }}
                                            </div>
                                        </div>
                                    </div>
                                    <!-- Status -->
                                    <div class="col-md-6 mb-3">
                                        <div class="p-2 bg-light rounded d-flex align-items-center">
                                            <i class="fas fa-info-circle me-2 text-info"></i>
                                            <span class="text-muted">Status</span>
                                            <div class="fw-bold ms-auto">{{ !empty($data->patientUser->status) ? __('messages.common.active') : __('messages.common.inactive') }}</div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <!-- Occupation -->
                                    <div class="col-md-6 mb-3">
                                        <div class="p-2 bg-light rounded d-flex align-items-center">
                                            <i class="fas fa-briefcase me-2 text-info"></i>
                                            <span class="text-muted">Occupation</span>
                                            <div class="fw-bold ms-auto">
                                                {{ !is_null($data->occupation) ? $data->occupation : __('messages.common.n/a') }}
                                            </div>
                                        </div>
                                    </div>
                                    <!-- Nationality -->
                                    <div class="col-md-6 mb-3">
                                        <div class="p-2 bg-light rounded d-flex align-items-center">
                                            <i class="fas fa-globe me-2 text-info"></i>
                                            <span class="text-muted">Nationality</span>
                                            <div class="fw-bold ms-auto"> {{ !is_null($data->nationality) ? $data->nationality : __('messages.common.n/a') }}</div>
                                        </div>
                                    </div>
                                </div>
                                <h5 class="card-title text-primary mb-4 mt-4">Other Information</h5>
                                <div class="row">
                                    <!-- Location -->
                                    <div class="col-md-6 mb-3">
                                        <div class="p-2 bg-light rounded d-flex align-items-center">
                                            <i class="fas fa-map-marker-alt me-2 text-info"></i> <!-- Location Icon -->
                                            <span class="text-muted">Location</span>
                                            <div class="fw-bold ms-auto">{{ !empty($data->patientUser->location) ? $data->patientUser->location : __('messages.common.n/a') }}</div>
                                        </div>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <div class="p-2 bg-light rounded d-flex align-items-center">
                                            <i class="fas fa-home me-2 text-primary"></i> <!-- Address Icon -->
                                            <span class="text-muted">Address 1 & Zip</span>
                                            <div class="fw-bold ms-auto">
                                                {{ !empty($patientAddress->address1) ? $patientAddress->address1 : __('messages.common.n/a') }},
                                                {{ !empty($patientAddress->zip) ? $patientAddress->zip : __('messages.common.n/a') }}
                                            </div>
                                        </div>
                                    </div>

                                    <!-- City -->
                                    <div class="col-md-6 mb-3">
                                        <div class="p-2 bg-light rounded d-flex align-items-center">
                                            <i class="fas fa-city me-2 text-info"></i>
                                            <span class="text-muted">City</span>
                                            <div class="fw-bold ms-auto">{{ !empty($patientAddress->city) ? $patientAddress->city : __('messages.common.n/a') }}</div>
                                        </div>
                                    </div>
                                </div>

                            </div>
                            <div class="d-flex justify-content-space-between align-items-center" style="margin-bottom: 20px;">
                                <marquee>
                                    <em>Created On - {{ !empty($data->patientUser->created_at) ? $data->patientUser->created_at : __('messages.common.n/a') }}</em>
                                    &nbsp;&nbsp;&nbsp;
                                    <em>Updated On - {{ !empty($data->patientUser->updated_at) ? $data->patientUser->updated_at : __('messages.common.n/a') }}</em>
                                </marquee>
                            </div>

                        </div>
                        <div class="card" style="width: 30rem;">
                            <div class="card-body">
                           <div class="card" style="background-color:#e5e5e5; padding: 1rem;">
                            <div class="d-flex justify-content-between">
                                <!-- Height -->
                                <div class="col-sm-4 d-flex flex-column mb-md-10 mb-5">
                                    <label for="name" class="pb-2 fs-5 text-gray-600">{{ __('messages.ipd_patient.height') . ':' }}</label>
                                    <span class="fs-5 text-gray-800">{{ !empty($vitals->height) ? $vitals->height : __('messages.common.n/a') }}</span>
                                </div>

                                <!-- Weight -->
                                <div class="col-sm-4 d-flex flex-column mb-md-10 mb-5">
                                    <label for="name" class="pb-2 fs-5 text-gray-600">{{ __('messages.ipd_patient.weight') . ':' }}</label>
                                    <span class="fs-5 text-gray-800">{{ !empty($vitals->weight) ? $vitals->weight : __('messages.common.n/a') }}</span>
                                </div>

                                <!-- Blood Pressure -->
                                <div class="col-sm-4 d-flex flex-column mb-md-10 mb-5">
                                    @if($vitals && !is_null($vitals->bp))
                                        @php
                                            $bpValues = explode('/', $vitals->bp);
                                            $systolic = (int)$bpValues[0];
                                            $diastolic = count($bpValues) > 1 ? (int)$bpValues[1] : "";

                                            if ($systolic > 180 || $diastolic > 120) {
                                                $bgColor = '#d00032'; // Hypertensive Crisis
                                                $color = '#e2d5d8';
                                                // $status = 'Hypertensive crisis. Consult your doctor immediately.';
                                            } elseif ($systolic >= 140 || $diastolic >= 90) {
                                                $bgColor = '#df5017'; // Hypertension Stage 2
                                                $color = '#fff';
                                                // $status = 'High blood pressure (Hypertension Stage 2). Consult your doctor.';
                                            } elseif (($systolic >= 130 && $systolic <= 139) || ($diastolic >= 80 && $diastolic <= 89)) {
                                                $bgColor = '#ec9612'; // Hypertension Stage 1
                                                $color = '#fff';
                                                // $status = 'High blood pressure (Hypertension Stage 1). Consult your doctor.';
                                            } elseif ($systolic >= 120 && $systolic <= 129 && $diastolic < 80) {
                                                $bgColor = '#d3e210'; // Elevated
                                                $color = '#fff';
                                                // $status = 'Elevated blood pressure. Monitor your blood pressure.';
                                            } else {
                                                $bgColor = '#a6e210'; // Normal
                                                $color = '#000';
                                                // $status = 'Normal blood pressure.';
                                            }
                                        @endphp

                                        <label for="name" class="pb-2 fs-5 text-gray-600">{{ __('messages.ipd_patient.bp') . ':' }}</label>
                                        <span class="fs-5 text-gray-800" style="background-color: {{ $bgColor }}; color: {{ $color }}; padding: 0.5rem; border-radius: 20px">
                                            {{ $vitals->bp }}
                                        </span>
                                    @else
                                        <span class="fs-5 text-gray-800">{{ __('messages.common.n/a') }}</span>
                                    @endif
                                </div>

                            </div>
                            <div>
                                <label for="name" class="pb-2 fs-5 text-gray-600">{{ __('messages.opd_patient.appointment_date') }}: </label>

                                @if(isset($opdPatientDepartment) && $opdPatientDepartment->appointment_date)
                                    <em class="fs-5 text-gray-800"
                                        title="{{ \Carbon\Carbon::parse($opdPatientDepartment->appointment_date)->diffForHumans() }}">
                                        {{ date('jS M, Y h:i A', strtotime($opdPatientDepartment->appointment_date)) }}
                                    </em>
                                @else
                                    <em class="fs-5 text-gray-800">No appointment date available</em>
                                @endif
                            </div>

                        </div>
                           <div class="row mt-4">

                            <div class="col-sm-6 d-flex flex-column mb-md-10 mb-5">
                                <label for="name"
                                    class="pb-2 fs-5 text-gray-600">{{ __('messages.user.phone') }}</label>
                                <p>
                                    <span
                                        class="fs-5 text-gray-800">{{ !empty($data->patientUser->phone) ? $data->patientUser->phone : __('messages.common.n/a') }}</span>
                                </p>
                            </div>
                            <div class="col-sm-6 d-flex flex-column mb-md-10 mb-5">
                            <label for="name"
                                class="pb-2 fs-5 text-gray-600">{{ __('messages.user.gender') }}</label>
                            <p>
                                <span
                                    class="fs-5 text-gray-800">{{ !empty($data->patientUser->phone) ? ($data->patientUser->gender != 1 ? __('messages.user.male') : __('messages.user.female')) : '' }}</span>
                            </p>
                        </div>
                        <div class="col-sm-6 d-flex flex-column mb-md-10 mb-5">
                            <label for="name"
                                class="pb-2 fs-5 text-gray-600">{{ __('messages.user.blood_group') }}</label>
                            <p>
                                @if (!empty($data->patientUser->blood_group))
                                    <span
                                        class="badge fs-6 bg-light-{{ !empty($data->patientUser->blood_group) ? 'success' : 'danger' }}">
                                        {{ $data->patientUser->blood_group }} </span>
                                @else
                                    <span class="fs-5 text-gray-800">{{ __('messages.common.n/a') }}</span>
                                @endif
                            </p>
                        </div>
                        <div class="col-sm-6 d-flex flex-column mb-md-10 mb-5">
                            <label for="name" class="pb-2 fs-5 text-gray-600">{{ __('messages.user.dob') }}</label>
                            <p>
                                <span
                                    class="fs-5 text-gray-800">{{ !empty($data->patientUser->dob) ? \Carbon\Carbon::parse($data->patientUser->dob)->translatedFormat('jS M, Y') : __('messages.common.n/a') }}</span>
                            </p>
                        </div>
                        <div class="col-sm-6 d-flex flex-column mb-md-10 mb-5">
                            <label for="name"
                                class="pb-2 fs-5 text-gray-600">{{ __('messages.common.created_at') }}</label>
                            <p>
                                <span
                                    class="fs-5 text-gray-800">{{ !empty($data->patientUser->created_at) ? $data->patientUser->created_at->diffForHumans() : __('messages.common.n/a') }}</span>
                            </p>
                        </div>
                        <div class="col-sm-6 d-flex flex-column mb-md-10 mb-5">
                            <label for="name"
                                class="pb-2 fs-5 text-gray-600">{{ __('messages.common.updated_at') }}</label>
                            <p>
                                <span
                                    class="fs-5 text-gray-800">{{ !empty($data->patientUser->updated_at) ? $data->patientUser->updated_at->diffForHumans() : __('messages.common.n/a') }}</span>
                            </p>
                        </div>
                        <div class="col-sm-6 d-flex flex-column mb-md-10 mb-5">
                            <label for="name"
                                class="pb-2 fs-5 text-gray-600">{{ __('messages.facebook_url') }}</label>
                            <p>
                                @if (!empty($doctorData->doctorUser->facebook_url))
                                    <a href="{{ $doctorData->doctorUser->facebook_url }}"
                                        class="fs-5 text-primary-800 text-decoration-none">{{ $doctorData->doctorUser->facebook_url }}</a>
                                @else
                                    {{ __('messages.common.n/a') }}
                                @endif
                            </p>
                        </div>
                        <div class="col-sm-6 d-flex flex-column mb-md-10 mb-5">
                            <label for="name"
                                class="pb-2 fs-5 text-gray-600">{{ __('messages.twitter_url') }}</label>
                            <p>
                                @if (!empty($doctorData->doctorUser->twitter_url))
                                    <a href="{{ $doctorData->doctorUser->twitter_url }}"
                                        class="fs-5 text-primary-800 text-decoration-none">{{ $doctorData->doctorUser->twitter_url }}</a>
                                @else
                                    {{ __('messages.common.n/a') }}
                                @endif
                            </p>
                        </div>
                        <div class="col-sm-6 d-flex flex-column mb-md-10 mb-5">
                            <label for="name"
                                class="pb-2 fs-5 text-gray-600">{{ __('messages.instagram_url') }}</label>
                            <p>
                                @if (!empty($doctorData->doctorUser->instagram_url))
                                    <a href="{{ $doctorData->doctorUser->instagram_url }}"
                                        class="fs-5 text-primary-800 text-decoration-none">{{ $doctorData->doctorUser->instagram_url }}</a>
                                @else
                                    {{ __('messages.common.n/a') }}
                                @endif
                            </p>
                        </div>
                        <div class="col-sm-6 d-flex flex-column mb-md-10 mb-5">
                            <label for="name"
                                class="pb-2 fs-5 text-gray-600">{{ __('messages.linkedIn_url') }}</label>
                            <p>
                                @if (!empty($doctorData->doctorUser->linkedIn_url))
                                    <a href="{{ $doctorData->doctorUser->linkedIn_url }}"
                                        class="fs-5 text-primary-800 text-decoration-none">{{ $doctorData->doctorUser->linkedIn_url }}</a>
                                @else
                                    {{ __('messages.common.n/a') }}
                                @endif
                            </p>
                        </div>

                            </div>
                        </div>
                    </div>
    </div>
    </div>
    </div>
    <span style="display: none;" id="spanPatientId">{{ $data->id ?? ''}}</span>
    <div class="tab-pane fade" id="showPatientCases" role="tabpanel">
        <livewire:patient-case-table patientId="{{ $data->id }}" />
    </div>
    <div class="tab-pane fade" id="showPatientAdmissions" role="tabpanel">
        <livewire:patient-admission-detail-table patientId="{{ $data->id }}" />
    </div>
    <div class="tab-pane fade" id="showPatientAppointments" role="tabpanel">
        <livewire:patient-appoinment-detail-table patientId="{{ $data->id }}" />
    </div>
    <div class="tab-pane fade" id="showPatientPrescriptions" role="tabpanel">
        <livewire:patient-prescription-detail-table patientId="{{ $data->id }}" />
    </div>

    <div class="tab-pane fade" id="showPatientPathology" role="tabpanel">
        <div class="d-flex justify-content-end mb-3">
            <a href="{{ route('pathology.test.create', ['ref_p_id' => $data->id]) }}" class="btn btn-primary">
                <i class="fas fa-plus me-2"></i>Request Pathology Test
            </a>
        </div>
        <livewire:pathology-tests-table patientId="{{ $data->id }}" />
    </div>

    @if(!$data->company_id)
    <div class="tab-pane fade" id="showPatientBills" role="tabpanel">
        <div class="row">
            <div class="col-12 mb-5">
                <h5 class="mb-4">{{ __('messages.bill.regular_bills') }}</h5>
                <livewire:patient-bill-detail-table patientId="{{ $data->id }}" />
            </div>
            <div class="col-12">
                <h5 class="mb-4">{{ __('messages.bill.ipd_bills') }}</h5>
                <livewire:patient-ipd-bill-table patientId="{{ $data->id }}" />
            </div>
        </div>
    </div>
    @endif
    <div class="tab-pane fade" id="showPatientInvoices" role="tabpanel">
        <livewire:patient-invoice-detail-table patientId="{{ $data->id }}" />
    </div>
    <div class="tab-pane fade" id="showPatientAdvancedPayments" role="tabpanel">
        <livewire:patient-advance-payment-detail-table patient-id="{{ $data->id }}" />
    </div>
    <div class="tab-pane fade" id="showPatientDocument" role="tabpanel">
        <livewire:patient-document-table patient-id="{{ $data->id }}" />
    </div>
    <div class="tab-pane fade" id="showPatientVaccinated" role="tabpanel">
        <livewire:patient-vaccination-detail-table patient-id="{{ $data->id }}" />
    </div>
    <div class="tab-pane fade" id="showPatientVitals" role="tabpanel">
        <livewire:vitals-table patientId="{{ $data->id }}" />
    </div>
</div>
