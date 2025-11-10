<div>
    <div class="card mb-5 mb-xl-10">
        <div class="card-body">
            <div class="row">
                <div class="col-xxl-5 col-12">
                    <div class="d-sm-flex align-items-center mb-5 mb-xxl-0 text-center text-sm-start">
                        <div class="image image-circle image-small">
                            <img src="{{ $maternity->patient->patientUser->image_url }}"
                                 class="object-fit-cover" alt="image"/>
                        </div>
                        <div class="ms-0 ms-md-10 mt-5 mt-sm-0">
                            <span class="badge bg-light-warning mb-2">{{ !empty($maternity->maternity_number) ? "#".$maternity->maternity_number : __('messages.common.n/a') }}</span>
                            <h2><a href="#"
                                   class="text-decoration-none">{{ $maternity->patient->patientUser->full_name }}</a>
                            </h2>
                            <a href="mailto:{{ $maternity->patient->patientUser->email }}"
                               class="text-gray-600 text-decoration-none fs-5">
                                {{ $maternity->patient->patientUser->email }}
                            </a>
                            <sapn class="d-flex align-items-center me-5 mb-2 mt-2">
                                @if(!empty($maternity->patient->address->address1) || !empty($maternity->patient->address->address2) || !empty($maternity->patient->address->city) || !empty($maternity->patient->address->zip))
                                    <span><i class="fas fa-location"></i></span>
                                @endif
                                {{ !empty($maternity->patient->address->address1) ? $maternity->patient->address->address1 : '' }}{{ !empty($maternity->patient->address->address2) ? !empty($maternity->patient->address->address1) ? ',' : '' : '' }}
                                {{ empty($maternity->patient->address->address1) || !empty($maternity->patient->address->address2)  ? !empty($maternity->patient->address->address2) ? $maternity->patient->address->address2 : '' : '' }}
                                {{ empty($maternity->patient->address->address1) && empty($maternity->patient->address->address2) ? '' : '' }}{{ !empty($maternity->patient->address->city) ? ','.$maternity->patient->address->city : '' }}{{ !empty($maternity->patient->address->zip) ? ','.$maternity->patient->address->zip : '' }}
                            </sapn>
                        </div>
                    </div>
                </div>
                <div class="col-xxl-7 col-12">
                    <div class="row justify-content-center">
                        <div class="col-md-4 col-sm-6 col-12 mb-6 mb-md-0">
                            <div class="border rounded-10 p-5 h-100">
                                <h2 class="text-primary mb-3">{{ !empty($maternity->patient->cases) ? $maternity->patient->cases->count() : 0 }}</h2>
                                <h3 class="fs-5 fw-light text-gray-600 mb-0">{{__('messages.patient.total_cases')}}</h3>
                            </div>
                        </div>
                        <div class="col-md-4 col-sm-6 col-12 mb-6 mb-md-0">
                            <div class="border rounded-10 p-5 h-100">
                                <h2 class="text-primary mb-3">{{ !empty($maternity->patient->admissions) ? $maternity->patient->admissions->count() : 0 }}</h2>
                                <h3 class="fs-5 fw-light text-gray-600 mb-0">{{__('messages.patient.total_admissions')}}</h3>
                            </div>
                        </div>
                        <div class="col-md-4 col-sm-6 col-12">
                            <div class="border rounded-10 p-5 h-100">
                                <h2 class="text-primary mb-3">{{ !empty($maternity->patient->appointments) ? $maternity->patient->appointments->count() : 0 }}</h2>
                                <h3 class="fs-5 fw-light text-gray-600 mb-0">{{__('messages.patient.total_appointments')}}</h3>
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
                   href="#maternityPatientOverview">{{ __('messages.overview') }}</a>
            </li>
        </ul>
    </div>

    <div class="tab-content" id="myTabContent">
        <div class="tab-pane fade show active" id="maternityPatientOverview" role="tabpanel">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-sm-6 d-flex flex-column mb-md-10 mb-5">
                            <label for="name"
                                   class="pb-2 fs-5 text-gray-600">{{ __('messages.case.case_id').':'  }}</label>
                            <span class="fs-5 text-gray-800">{{ !empty($maternity->case_id) ? $maternity->patientCase->case_id : __('messages.common.n/a') }}</span>
                        </div>
                        <div class="col-sm-6 d-flex flex-column mb-md-10 mb-5">
                            <label for="name"
                                   class="pb-2 fs-5 text-gray-600">{{ __('messages.case.doctor').':'  }}</label>
                            <span class="fs-5 text-gray-800">{{ $maternity->doctor->doctorUser->full_name }}</span>
                        </div>
                        <div class="col-sm-6 d-flex flex-column mb-md-10 mb-5">
                            <label for="name"
                                   class="pb-2 fs-5 text-gray-600">{{ __('messages.maternity_patient.appointment_date').':'  }}</label>
                            <span class="fs-5 text-gray-800">{{ !empty($maternity->appointment_date) ? \Carbon\Carbon::parse($maternity->appointment_date)->translatedFormat('jS M, Y g:i A') : __('messages.common.n/a') }}</span>
                        </div>
                        <div class="col-sm-6 d-flex flex-column mb-md-10 mb-5">
                            <label for="name"
                                   class="pb-2 fs-5 text-gray-600">{{ __('messages.ipd_patient.height').':'  }}</label>
                            <span class="fs-5 text-gray-800">{{ !empty($maternity->height) ? $maternity->height : __('messages.common.n/a') }}</span>
                        </div>
                        <div class="col-sm-6 d-flex flex-column mb-md-10 mb-5">
                            <label for="name"
                                   class="pb-2 fs-5 text-gray-600">{{ __('messages.ipd_patient.weight').':'  }}</label>
                            <span class="fs-5 text-gray-800">{{ !empty($maternity->weight) ? $maternity->weight : __('messages.common.n/a') }}</span>
                        </div>
                        <div class="col-sm-6 d-flex flex-column mb-md-10 mb-5">
                            <label for="name"
                                   class="pb-2 fs-5 text-gray-600">{{ __('messages.ipd_patient.bp').':' }}</label>
                            <span class="fs-5 text-gray-800">{{ !empty($maternity->bp) ? $maternity->bp : __('messages.common.n/a') }}</span>
                        </div>
                        <div class="col-sm-6 d-flex flex-column mb-md-10 mb-5">
                            <label for="name"
                                   class="pb-2 fs-5 text-gray-600">{{ __('messages.ipd_payments.payment_mode') }}</label>
                            <span class="fs-5 text-gray-800">{{ !empty($maternity->payment_mode_name) ? $maternity->payment_mode_name : __('messages.common.n/a') }}</span>
                        </div>
                        <div class="col-sm-6 d-flex flex-column mb-md-10 mb-5">
                            <label for="name"
                                   class="pb-2 fs-5 text-gray-600">{{ __('messages.doctor_maternity_charge.standard_charge') }}</label>
                            <span class="fs-5 text-gray-800">
                                @if(!empty($maternity->standard_charge))
                                    {{ checkNumberFormat($maternity->standard_charge, strtoupper(getCurrentCurrency())) }}
                                @else
                                    {{ __('messages.common.n/a') }}
                                @endif
                            </span>
                        </div>
                        <div class="col-sm-6 d-flex flex-column mb-md-10 mb-5">
                            <label for="name"
                                   class="pb-2 fs-5 text-gray-600">{{ __('messages.ipd_patient.is_old_patient').':' }}</label>
                            <span class="fs-5 text-gray-800">{{ ($maternity->is_old_patient) ? __('messages.common.yes') : __('messages.common.no') }}</span>
                        </div>
                        <div class="col-sm-6 d-flex flex-column mb-md-10 mb-5">
                            <label for="name"
                                   class="pb-2 fs-5 text-gray-600">{{ __('messages.common.created_at').':' }}</label>
                            <span class="fs-5 text-gray-800">{{ !empty($maternity->created_at) ? $maternity->created_at->diffForHumans() : __('messages.common.n/a') }}</span>
                        </div>
                        <div class="col-sm-6 d-flex flex-column mb-md-10 mb-5">
                            <label for="name"
                                   class="pb-2 fs-5 text-gray-600">{{ __('messages.common.updated_at').':' }}</label>
                            <span class="fs-5 text-gray-800">{{ !empty($maternity->updated_at) ? $maternity->updated_at->diffForHumans() : __('messages.common.n/a') }}</span>
                        </div>
                        <div class="col-sm-6 d-flex flex-column mb-md-10 mb-5">
                            <label for="name"
                                   class="pb-2 fs-5 text-gray-600">{{ __('messages.ipd_patient.symptoms').':' }}</label>
                            <span class="fs-5 text-gray-800">{!!  !empty($maternity->symptoms) ? nl2br(e($maternity->symptoms)) : __('messages.common.n/a')  !!}</span>
                        </div>
                        <div class="col-sm-6 d-flex flex-column mb-md-10 mb-5">
                            <label for="name"
                                   class="pb-2 fs-5 text-gray-600">{{ __('messages.ipd_patient.notes').':' }}</label>
                            <span class="fs-5 text-gray-800">{!! !empty($maternity->notes) ? nl2br(e($maternity->notes)) : __('messages.common.n/a')  !!}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
