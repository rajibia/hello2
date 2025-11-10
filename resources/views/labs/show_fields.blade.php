<div>
    <div class="tab-content" id="myLabTabContent">
        <div class="tab-pane fade show active" id="labPoverview" role="tabpanel">
            <div class="card mb-5 mb-xl-10">
                <div class="card-header border-0">
                    <div class="card-title m-0">
                        <h3 class="fw-bolder m-0">  {{ __('messages.lab.lab_details') }}</h3>
                    </div>
                </div>
                <div>
                    <div class="card-body  border-top p-9">
                        <div class="row mb-7">
                            <div class="col-lg-3 d-flex flex-column">
                                <label class="fw-bold text-muted py-3">{{ __('messages.package.lab').(' Name:')  }}</label>
                                <span class="fw-bolder fs-6 text-gray-800">{{ $lab->name}}</span>
                            </div>
                       
                            <div class="col-lg-3 d-flex flex-column">
                                <label class="fw-bold text-muted py-3">Insurance:</label>
                                <span class="fw-bolder fs-6 text-gray-800">{{ $lab->insurance_name}}</span>
                            </div>
                            <div class="col-lg-3 d-flex flex-column">
                                <label class="fw-bold text-muted py-3">G-DRG code:</label>
                                <span class="fw-bolder fs-6 text-gray-800">{{ $lab->gdrg_code}}</span>
                            </div>
                            <div class="col-lg-3 d-flex flex-column">
                                <label class="fw-bold text-muted py-3">{{ __('messages.lab.rate').(' / Tariff:')  }}</label>
                                <span class="fw-bolder fs-6 text-gray-800">{{ getCurrencySymbol() }}</b> {{ number_format($lab->rate,2)}}</span>
                            </div>
                            <div class="col-lg-3 d-flex flex-column">
                                <label class="fw-bold text-muted py-3">Topup:</label>
                                <span class="fw-bolder fs-6 text-gray-800">{{ getCurrencySymbol() }}</b> {{ number_format($lab->topup,2)}}</span>
                            </div>
                            <div class="col-lg-3 d-flex flex-column">
                                <label class="fw-bold text-muted py-3">Non-insured amount</label>
                                <span class="fw-bolder fs-6 text-gray-800">{{ getCurrencySymbol() }}</b> {{ number_format($lab->non_insured_amount,2)}}</span>
                            </div>
                            <div class="col-lg-3 d-flex flex-column">
                                <label class="fw-bold text-muted py-3">{{ __('messages.common.status').(':')  }}</label>
                                <p class="m-0"><span class="badge bg-light-{{!empty($lab->status == 1) ? 'success' : 'danger'}}">{{  ($lab->status == 1) ? __('messages.common.active') : __('messages.common.de_active') }}</span>
                                </p>
                            </div>
                            <div class="col-lg-3 d-flex flex-column">
                                <label class="fw-bold text-muted py-3">{{ __('messages.common.created_at').(':')  }}</label>
                                <span class="fw-bolder fs-6 text-gray-800" data-placement="top"  data-bs-original-title="{{ date('jS M, Y', strtotime($lab->created_at)) }}">{{ $lab->created_at->diffForHumans() }}</span>
                            </div>
                            <div class="col-lg-3 d-flex flex-column">
                                <label class="fw-bold text-muted py-3">{{ __('messages.common.last_updated').(':')  }}</label>
                                <span class="fw-bolder fs-6 text-gray-800" data-placement="top"  data-bs-original-title="{{ date('jS M, Y', strtotime($lab->updated_at)) }}">{{ $lab->updated_at->diffForHumans() }}</span>
                            </div>
                           
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
