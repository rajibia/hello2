<div>
    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-xxl-5 col-12">
                    <div class="d-sm-flex align-items-center mb-5 mb-xxl-0 text-center text-sm-start">
                        <div class="ms-0 ms-md-10 mt-5 mt-sm-0">
                            <h2><a href="javascript:void(0)"
                                    class="text-decoration-none">{{ !empty($data->name) ? $data->name : '' }}</a>
                            </h2>

                            <a href="mailto:{{ !empty($data->email) ? $data->email : '' }}"
                                class="text-gray-600 text-decoration-none fs-5">
                                {{ !empty($data->email) ? $data->email : '' }}
                            </a>
                            <span class="d-flex align-items-center me-2 mb-2 mt-2">
                                @if (!empty($data->address) || !empty($data->address->city))
                                    <span><i class="fas fa-location"></i></span>
                                @endif
                                <span class="p-2">
                                    {{ !empty($data->address) ? $data->address : '' }}
                                </span>
                            </span>
                        </div>
                    </div>
                </div>
                <div class="col-xxl-7 col-12">
                    <div class="row justify-content-start">
                        <div class="col-md-6 col-sm-6 col-12 mb-6 mb-md-0">
                            <div class="border rounded-10 p-5 h-100">
                                <h2 class="text-primary mb-3">{{ !empty($data->purchasemedicines) ? $data->purchasemedicines->count() : 0 }}
                                </h2>
                                <h3 class="fs-5 fw-light text-gray-600 mb-0">{{ __('messages.purchase_medicine.purchase_medicine') }}
                                </h3>
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
                    href="#SupplierOverview">{{ __('messages.overview') }}</a>
            </li>
            <li class="nav-item position-relative me-7 mb-3">
                <a class="nav-link p-0" data-bs-toggle="tab" href="#showSupplierCases">{{ __('messages.purchase_medicine.purchase_medicine') }}</a>
            </li>
        </ul>
    </div>
</div>
<div class="tab-content" id="mySupplierTabContent">
    <div class="tab-pane fade show active" id="SupplierOverview" role="tabpanel">
        <div class="card mb-5 mb-xl-10">
            <div>
                <div class="card-body  border-top p-9">
                    <div class="row">
                        <div class="col-sm-6 d-flex flex-column mb-md-10 mb-5">
                            <label for="name"
                                class="pb-2 fs-5 text-gray-600">{{ __('messages.user.phone') }}</label>
                            <p>
                                <span
                                    class="fs-5 text-gray-800">{{ !empty($data->phone) ? $data->phone : __('messages.common.n/a') }}</span>
                            </p>
                        </div>
                       
                        
                       
                        <div class="col-sm-6 d-flex flex-column mb-md-10 mb-5">
                            <label for="name"
                                class="pb-2 fs-5 text-gray-600">{{ __('messages.common.created_at') }}</label>
                            <p>
                                <span
                                    class="fs-5 text-gray-800">{{ !empty($data->created_at) ? $data->created_at->diffForHumans() : __('messages.common.n/a') }}</span>
                            </p>
                        </div>
                        <div class="col-sm-6 d-flex flex-column mb-md-10 mb-5">
                            <label for="name"
                                class="pb-2 fs-5 text-gray-600">{{ __('messages.common.updated_at') }}</label>
                            <p>
                                <span
                                    class="fs-5 text-gray-800">{{ !empty($data->updated_at) ? $data->updated_at->diffForHumans() : __('messages.common.n/a') }}</span>
                            </p>
                        </div>
                       
                        
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="tab-pane fade" id="showSupplierCases" role="tabpanel">
        <livewire:supplier-purchase-medicine-table supplierId="{{ $data->id }}" />
    </div>
    
</div>
