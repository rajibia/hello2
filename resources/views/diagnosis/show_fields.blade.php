<div>
    <div class="tab-content" id="myDiagnosisTabContent">
        <div class="tab-pane fade show active" id="diagnosisPoverview" role="tabpanel">
            <div class="card mb-5 mb-xl-10">
                <div class="card-header border-0">
                    <div class="card-title m-0">
                        <h3 class="fw-bolder m-0">  {{ __('messages.diagnosis.diagnosis_details') }}</h3>
                    </div>
                </div>
                <div>
                    <div class="card-body  border-top p-9">
                        <div class="row mb-7">
                            <div class="col-lg-3 d-flex flex-column">
                                <label class="fw-bold text-muted py-3">{{ __('messages.package.diagnosis').(':')  }}</label>
                                <span class="fw-bolder fs-6 text-gray-800">{{ $diagnosis->name}}</span>
                            </div>
                       
                            <div class="col-lg-3 d-flex flex-column">
                                <label class="fw-bold text-muted py-3">{{ ('Insurance:')  }}</label>
                                <span class="fw-bolder fs-6 text-gray-800">{{ $diagnosis->insurance_name}}</span>
                            </div> 
                            <div class="col-lg-3 d-flex flex-column">
                                <label class="fw-bold text-muted py-3">{{ ('Age:')  }}</label>
                                <span class="fw-bolder fs-6 text-gray-800">{{ $diagnosis->age}}</span>
                            </div>
                            <div class="col-lg-3 d-flex flex-column">
                                <label class="fw-bold text-muted py-3">{{ ('Grouping:')  }}</label>
                                <span class="fw-bolder fs-6 text-gray-800">{{ $diagnosis->grouping}}</span>
                            </div>
                            
                            <div class="col-lg-3 d-flex flex-column">
                                <label class="fw-bold text-muted py-3">G-DRG code:</label>
                                <span class="fw-bolder fs-6 text-gray-800">{{ $diagnosis->gdrg_code}}</span>
                            </div>
                            <div class="col-lg-3 d-flex flex-column">
                                <label class="fw-bold text-muted py-3">ICD 10 code:</label>
                                <span class="fw-bolder fs-6 text-gray-800">{{ $diagnosis->icd_10_code}}</span>
                            </div>
                            <div class="col-lg-3 d-flex flex-column">
                                <label class="fw-bold text-muted py-3">{{ ('Speciality Code:')  }}</label>
                                <span class="fw-bolder fs-6 text-gray-800">{{ $diagnosis->speciality_code}}</span>
                            </div>
                            <div class="col-lg-3 d-flex flex-column">
                                <label class="fw-bold text-muted py-3">{{ ('Speciality Description:')  }}</label>
                                <span class="fw-bolder fs-6 text-gray-800">{{ $diagnosis->speciality_description}}</span>
                            </div>
                            <div class="col-lg-3 d-flex flex-column">
                                <label class="fw-bold text-muted py-3">{{ __('messages.diagnosis.rate').(' / Tariff:')  }}</label>
                                <span class="fw-bolder fs-6 text-gray-800">{{ getCurrencySymbol() }}</b> {{ number_format($diagnosis->tariff,2)}}</span>
                            </div>
                            <div class="col-lg-3 d-flex flex-column">
                                <label class="fw-bold text-muted py-3">Topup:</label>
                                <span class="fw-bolder fs-6 text-gray-800">{{ getCurrencySymbol() }}</b> {{ number_format($diagnosis->topup,2)}}</span>
                            </div>
                            <div class="col-lg-3 d-flex flex-column">
                                <label class="fw-bold text-muted py-3">Non-insured amount</label>
                                <span class="fw-bolder fs-6 text-gray-800">{{ getCurrencySymbol() }}</b> {{ number_format($diagnosis->non_insured_amount,2)}}</span>
                            </div>
                            <div class="col-lg-3 d-flex flex-column">
                                <label class="fw-bold text-muted py-3">{{ __('messages.common.status').(':')  }}</label>
                                <p class="m-0"><span class="badge bg-light-{{!empty($diagnosis->status == 1) ? 'success' : 'danger'}}">{{  ($diagnosis->status == 1) ? __('messages.common.active') : __('messages.common.de_active') }}</span>
                                </p>
                            </div>
                            <div class="col-lg-3 d-flex flex-column">
                                <label class="fw-bold text-muted py-3">{{ __('messages.common.created_at').(':')  }}</label>
                                <span class="fw-bolder fs-6 text-gray-800" data-placement="top"  data-bs-original-title="{{ date('jS M, Y', strtotime($diagnosis->created_at)) }}">{{ $diagnosis->created_at->diffForHumans() }}</span>
                            </div>
                            <div class="col-lg-3 d-flex flex-column">
                                <label class="fw-bold text-muted py-3">{{ __('messages.common.last_updated').(':')  }}</label>
                                <span class="fw-bolder fs-6 text-gray-800" data-placement="top"  data-bs-original-title="{{ date('jS M, Y', strtotime($diagnosis->updated_at)) }}">{{ $diagnosis->updated_at->diffForHumans() }}</span>
                            </div>
                           
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
