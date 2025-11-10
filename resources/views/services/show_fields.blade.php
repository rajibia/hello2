<div>
    <div class="tab-content" id="myServiceTabContent">
        <div class="tab-pane fade show active" id="servicePoverview" role="tabpanel">
            <div class="card mb-5 mb-xl-10">
                <div class="card-header border-0">
                    <div class="card-title m-0">
                        <h3 class="fw-bolder m-0">  {{ __('messages.service.service_details') }}</h3>
                    </div>
                </div>
                <div>
                    <div class="card-body  border-top p-9">
                        <div class="row mb-7">
                            <div class="col-lg-3 d-flex flex-column">
                                <label class="fw-bold text-muted py-3">{{ __('messages.package.service').(':')  }}</label>
                                <span class="fw-bolder fs-6 text-gray-800">{{ $service->name}}</span>
                            </div>
                            {{-- <div class="col-lg-3 d-flex flex-column">
                                <label class="fw-bold text-muted py-3">{{ __('messages.service.quantity').(':')  }}</label>
                                <span class="fw-bolder fs-6 text-gray-800">{{ $service->quantity}}</span>
                            </div> --}}
                            <div class="col-lg-3 d-flex flex-column">
                                <label class="fw-bold text-muted py-3">{{ ('Insurance:')  }}</label>
                                <span class="fw-bolder fs-6 text-gray-800">{{ $service->insurance_name}}</span>
                            </div> 
                            <div class="col-lg-3 d-flex flex-column">
                                <label class="fw-bold text-muted py-3">{{ ('Age:')  }}</label>
                                <span class="fw-bolder fs-6 text-gray-800">{{ $service->age}}</span>
                            </div>
                            <div class="col-lg-3 d-flex flex-column">
                                <label class="fw-bold text-muted py-3">{{ ('Speciality Code:')  }}</label>
                                <span class="fw-bolder fs-6 text-gray-800">{{ $service->speciality_code}}</span>
                            </div>
                            <div class="col-lg-3 d-flex flex-column">
                                <label class="fw-bold text-muted py-3">{{ ('G - DRG Code:')  }}</label>
                                <span class="fw-bolder fs-6 text-gray-800">{{ $service->icd_code}}</span>
                            </div>
                            <div class="col-lg-3 d-flex flex-column">
                                <label class="fw-bold text-muted py-3">{{ ('Charge status:')  }}</label>
                                <span class="fw-bolder fs-6 text-gray-800">{{ $service->charge_status}}</span>
                            </div>
                            <div class="col-lg-3 d-flex flex-column">
                                <label class="fw-bold text-muted py-3">{{ __('messages.service.rate').(':')  }}</label>
                                <span class="fw-bolder fs-6 text-gray-800">{{ getCurrencySymbol() }}</b> {{ number_format($service->rate,2)}}</span>
                            </div>
                            <div class="col-lg-3 d-flex flex-column">
                                <label class="fw-bold text-muted py-3">{{ ('Top up:')  }}</label>
                                <span class="fw-bolder fs-6 text-gray-800">{{ getCurrencySymbol() }}</b> {{ number_format($service->topup,2)}}</span>
                            </div>
                            <div class="col-lg-3 d-flex flex-column">
                                <label class="fw-bold text-muted py-3">{{ ('Non-insured amount:')  }}</label>
                                <span class="fw-bolder fs-6 text-gray-800">{{ getCurrencySymbol() }}</b> {{ number_format($service->non_insured_amount,2)}}</span>
                            </div>
                            <div class="col-lg-3 d-flex flex-column">
                                <label class="fw-bold text-muted py-3">{{ __('messages.common.status').(':')  }}</label>
                                <p class="m-0"><span class="badge bg-light-{{!empty($service->status == 1) ? 'success' : 'danger'}}">{{  ($service->status == 1) ? __('messages.common.active') : __('messages.common.de_active') }}</span>
                                </p>
                            </div>
                            <div class="col-lg-3 d-flex flex-column">
                                <label class="fw-bold text-muted py-3">{{ __('messages.common.created_at').(':')  }}</label>
                                <span class="fw-bolder fs-6 text-gray-800" data-placement="top"  data-bs-original-title="{{ date('jS M, Y', strtotime($service->created_at)) }}">{{ $service->created_at->diffForHumans() }}</span>
                            </div>
                            <div class="col-lg-3 d-flex flex-column">
                                <label class="fw-bold text-muted py-3">{{ __('messages.common.last_updated').(':')  }}</label>
                                <span class="fw-bolder fs-6 text-gray-800" data-placement="top"  data-bs-original-title="{{ date('jS M, Y', strtotime($service->updated_at)) }}">{{ $service->updated_at->diffForHumans() }}</span>
                            </div>
                            <div class="col-lg-3 d-flex flex-column">
                                <label class="fw-bold text-muted py-3">Speciality {{ __('messages.common.description').(':')  }}</label>
                                <span class="fw-bolder fs-6 text-gray-800">{!! !empty($service->description)?nl2br(e($service->description)):__('messages.common.n/a') !!}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
