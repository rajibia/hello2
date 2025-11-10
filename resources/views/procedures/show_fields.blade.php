<div>
    <div class="tab-content" id="myProcedureTabContent">
        <div class="tab-pane fade show active" id="procedurePoverview" role="tabpanel">
            <div class="card mb-5 mb-xl-10">
                <div class="card-header border-0">
                    <div class="card-title m-0">
                        <h3 class="fw-bolder m-0">  {{ __('messages.procedure.procedure_details') }}</h3>
                    </div>
                </div>
                <div>
                    <div class="card-body  border-top p-9">
                        <div class="row mb-7">
                            <div class="col-lg-3 d-flex flex-column">
                                <label class="fw-bold text-muted py-3">{{ __('messages.package.procedure').(':')  }}</label>
                                <span class="fw-bolder fs-6 text-gray-800">{{ $procedure->name}}</span>
                            </div>
                       
                            <div class="col-lg-3 d-flex flex-column">
                                <label class="fw-bold text-muted py-3">{{ ('Insurance:')  }}</label>
                                <span class="fw-bolder fs-6 text-gray-800">{{ $procedure->insurance_name}}</span>
                            </div> 
                            <div class="col-lg-3 d-flex flex-column">
                                <label class="fw-bold text-muted py-3">{{ ('Age:')  }}</label>
                                <span class="fw-bolder fs-6 text-gray-800">{{ $procedure->age}}</span>
                            </div>
                            <div class="col-lg-3 d-flex flex-column">
                                <label class="fw-bold text-muted py-3">{{ ('Grouping:')  }}</label>
                                <span class="fw-bolder fs-6 text-gray-800">{{ $procedure->grouping}}</span>
                            </div>
                            
                            <div class="col-lg-3 d-flex flex-column">
                                <label class="fw-bold text-muted py-3">G-DRG code:</label>
                                <span class="fw-bolder fs-6 text-gray-800">{{ $procedure->gdrg_code}}</span>
                            </div>
                            <div class="col-lg-3 d-flex flex-column">
                                <label class="fw-bold text-muted py-3">{{ ('Speciality Code:')  }}</label>
                                <span class="fw-bolder fs-6 text-gray-800">{{ $procedure->speciality_code}}</span>
                            </div>
                            <div class="col-lg-3 d-flex flex-column">
                                <label class="fw-bold text-muted py-3">{{ ('Speciality Description:')  }}</label>
                                <span class="fw-bolder fs-6 text-gray-800">{{ $procedure->speciality_description}}</span>
                            </div>
                            <div class="col-lg-3 d-flex flex-column">
                                <label class="fw-bold text-muted py-3">{{ __('messages.procedure.rate').(' / Tariff:')  }}</label>
                                <span class="fw-bolder fs-6 text-gray-800">{{ getCurrencySymbol() }}</b> {{ number_format($procedure->tariff,2)}}</span>
                            </div>
                            <div class="col-lg-3 d-flex flex-column">
                                <label class="fw-bold text-muted py-3">Topup:</label>
                                <span class="fw-bolder fs-6 text-gray-800">{{ getCurrencySymbol() }}</b> {{ number_format($procedure->topup,2)}}</span>
                            </div>
                            <div class="col-lg-3 d-flex flex-column">
                                <label class="fw-bold text-muted py-3">Non-insured amount</label>
                                <span class="fw-bolder fs-6 text-gray-800">{{ getCurrencySymbol() }}</b> {{ number_format($procedure->non_insured_amount,2)}}</span>
                            </div>
                            <div class="col-lg-3 d-flex flex-column">
                                <label class="fw-bold text-muted py-3">{{ __('messages.common.status').(':')  }}</label>
                                <p class="m-0"><span class="badge bg-light-{{!empty($procedure->status == 1) ? 'success' : 'danger'}}">{{  ($procedure->status == 1) ? __('messages.common.active') : __('messages.common.de_active') }}</span>
                                </p>
                            </div>
                            <div class="col-lg-3 d-flex flex-column">
                                <label class="fw-bold text-muted py-3">{{ __('messages.common.created_at').(':')  }}</label>
                                <span class="fw-bolder fs-6 text-gray-800" data-placement="top"  data-bs-original-title="{{ date('jS M, Y', strtotime($procedure->created_at)) }}">{{ $procedure->created_at->diffForHumans() }}</span>
                            </div>
                            <div class="col-lg-3 d-flex flex-column">
                                <label class="fw-bold text-muted py-3">{{ __('messages.common.last_updated').(':')  }}</label>
                                <span class="fw-bolder fs-6 text-gray-800" data-placement="top"  data-bs-original-title="{{ date('jS M, Y', strtotime($procedure->updated_at)) }}">{{ $procedure->updated_at->diffForHumans() }}</span>
                            </div>
                           
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
