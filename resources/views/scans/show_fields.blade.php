<div>
    <div class="tab-content" id="myScanTabContent">
        <div class="tab-pane fade show active" id="scanPoverview" role="tabpanel">
            <div class="card mb-5 mb-xl-10">
                <div class="card-header border-0">
                    <div class="card-title m-0">
                        <h3 class="fw-bolder m-0">  {{ __('messages.scan.scan_details') }}</h3>
                    </div>
                </div>
                <div>
                    <div class="card-body  border-top p-9">
                        <div class="row mb-7">
                            <div class="col-lg-3 d-flex flex-column">
                                <label class="fw-bold text-muted py-3">{{ __('messages.package.scan').(':')  }}</label>
                                <span class="fw-bolder fs-6 text-gray-800">{{ $scan->name}}</span>
                            </div>
                       
                            <div class="col-lg-3 d-flex flex-column">
                                <label class="fw-bold text-muted py-3">Insurance:</label>
                                <span class="fw-bolder fs-6 text-gray-800">{{ $scan->insurance_name}}</span>
                            </div>
                            <div class="col-lg-3 d-flex flex-column">
                                <label class="fw-bold text-muted py-3">Flag:</label>
                                <span class="fw-bolder fs-6 text-gray-800">{{ $scan->flag}}</span>
                            </div>
                            <div class="col-lg-3 d-flex flex-column">
                                <label class="fw-bold text-muted py-3">G-DRG code:</label>
                                <span class="fw-bolder fs-6 text-gray-800">{{ $scan->gdrg_code}}</span>
                            </div>
                            <div class="col-lg-3 d-flex flex-column">
                                <label class="fw-bold text-muted py-3">{{ __('messages.scan.rate').(' / Tariff:')  }}</label>
                                <span class="fw-bolder fs-6 text-gray-800">{{ getCurrencySymbol() }}</b> {{ number_format($scan->rate,2)}}</span>
                            </div>
                            <div class="col-lg-3 d-flex flex-column">
                                <label class="fw-bold text-muted py-3">Topup:</label>
                                <span class="fw-bolder fs-6 text-gray-800">{{ getCurrencySymbol() }}</b> {{ number_format($scan->topup,2)}}</span>
                            </div>
                            <div class="col-lg-3 d-flex flex-column">
                                <label class="fw-bold text-muted py-3">Non-insured amount</label>
                                <span class="fw-bolder fs-6 text-gray-800">{{ getCurrencySymbol() }}</b> {{ number_format($scan->non_insured_amount,2)}}</span>
                            </div>
                            <div class="col-lg-3 d-flex flex-column">
                                <label class="fw-bold text-muted py-3">{{ __('messages.common.status').(':')  }}</label>
                                <p class="m-0"><span class="badge bg-light-{{!empty($scan->status == 1) ? 'success' : 'danger'}}">{{  ($scan->status == 1) ? __('messages.common.active') : __('messages.common.de_active') }}</span>
                                </p>
                            </div>
                            <div class="col-lg-3 d-flex flex-column">
                                <label class="fw-bold text-muted py-3">{{ __('messages.common.created_at').(':')  }}</label>
                                <span class="fw-bolder fs-6 text-gray-800" data-placement="top"  data-bs-original-title="{{ date('jS M, Y', strtotime($scan->created_at)) }}">{{ $scan->created_at->diffForHumans() }}</span>
                            </div>
                            <div class="col-lg-3 d-flex flex-column">
                                <label class="fw-bold text-muted py-3">{{ __('messages.common.last_updated').(':')  }}</label>
                                <span class="fw-bolder fs-6 text-gray-800" data-placement="top"  data-bs-original-title="{{ date('jS M, Y', strtotime($scan->updated_at)) }}">{{ $scan->updated_at->diffForHumans() }}</span>
                            </div>
                           
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
