<div>
    <div class="tab-content" id="myInsuranceTabContent">
        <div class="tab-pane fade show active" id="insurancePoverview" role="tabpanel">
            <div class="card mb-5 mb-xl-10">
                <div>
                    <div class="card-body">
                        <div class="row mb-7">
                            <div class="col-lg-3 d-flex flex-column mb-md-10 mb-5">
                                <div class="symbol symbol-100px symbol-lg-160px symbol-fixed position-relative">
                                    <label
                                    class="pb-2 fs-5 text-gray-600">Logo:</label>
                                    <img src="{{$insurance->image}}" alt="image" class="img-fluid img-reponsive object-fit-cover"/>
                                </div>
                            </div>

                            <div class="col-lg-3 d-flex flex-column mb-md-10 mb-5">
                                <label
                                    class="pb-2 fs-5 text-gray-600">{{ __('messages.insurance.insurance') . ':' }}</label>
                                <span class="fs-5 text-gray-800">{{ $insurance->name ?? '--' }}</span>
                            </div>
                            <div class="col-lg-3 d-flex flex-column mb-md-10 mb-5">
                                <label
                                    class="pb-2 fs-5 text-gray-600">{{ __('messages.insurance.insurance_code') . ':' }}</label>
                                <span class="fs-5 text-gray-800">{{ $insurance->insurance_code ?? '--' }}</span>
                            </div>
       
                            <div class="col-lg-3 d-flex flex-column mb-md-10 mb-5">
                                <label
                                    class="pb-2 fs-5 text-gray-600">{{ 'Other Identification:' }}</label>
                                <span class="fs-5 text-gray-800">
                                    {{ $insurance->other_identification ?? '--' }}
                                </span>
                            </div>
                            <div class="col-lg-3 d-flex flex-column mb-md-10 mb-5">
                                <label
                                    class="pb-2 fs-5 text-gray-600">{{ 'Card Type:' }}</label>
                                <span
                                    class="fs-5 text-gray-800">{{ $insurance->card_type ?? '--' }}</span>
                            </div>
                            <div class="col-lg-3 d-flex flex-column mb-md-10 mb-5">
                                <label
                                    class="pb-2 fs-5 text-gray-600">{{ 'Claim Code Count:' }}</label>
                                <span class="fs-5 text-gray-800">{{ $insurance->claim_code_count ?? '--' }}</span>
                            </div>
                            <div class="col-lg-3 d-flex flex-column mb-md-10 mb-5">
                                <label
                                    class="pb-2 fs-5 text-gray-600">{{ 'Membership Number Count:' }}</label>
                                <span class="fs-5 text-gray-800">{{ $insurance->membership_no_count ?? '--' }}</span>
                            </div>
                            <div class="col-lg-3 d-flex flex-column mb-md-10 mb-5">
                                <label
                                    class="pb-2 fs-5 text-gray-600">{{ 'Card Serial Number Count:' }}</label>
                                <span class="fs-5 text-gray-800">{{ $insurance->card_serial_no_count ?? '--' }}</span>
                            </div>
                            <div class="col-lg-3 d-flex flex-column mb-md-10 mb-5">
                                <label
                                    class="pb-2 fs-5 text-gray-600">{{ 'Visits Per Month:' }}</label>
                                <span class="fs-5 text-gray-800">{{ $insurance->visit_per_month  ?? '--' }}</span>
                            </div>
                            
                            <div class="col-lg-3 d-flex flex-column mb-md-10 mb-5">
                                <label class="pb-2 fs-5 text-gray-600">{{ __('messages.common.status') . ':' }}</label>
                                <p class="m-0">
                                    <span
                                        class="badge fs-6 bg-light-{{ !empty($insurance->status === 1) ? 'success' : 'danger' }}">{{ $insurance->status === 1 ? __('messages.common.active') : __('messages.common.de_active') }}</span>
                                </p>
                            </div>
                            {{-- <div class="col-lg-3 d-flex flex-column">
                                <label
                                    class="pb-2 fs-5 text-gray-600">{{ __('messages.insurance.remark') . ':' }}</label>
                                <span class="fs-5 text-gray-800">{!! !empty($insurance->remark) ? nl2br(e($insurance->remark)) : __('messages.common.n/a') !!}</span>
                            </div> --}}
                            <div class="col-lg-3 d-flex flex-column">
                                <label
                                    class="pb-2 fs-5 text-gray-600">{{ __('messages.common.created_at') . ':' }}</label>
                                <span class="fs-5 text-gray-800">{{ $insurance->created_at->diffForHumans() }}</span>
                            </div>
                            <div class="col-lg-3 d-flex flex-column">
                                <label
                                    class="pb-2 fs-5 text-gray-600">{{ __('messages.common.last_updated') . ':' }}</label>
                                <span class="fs-5 text-gray-800">{{ $insurance->updated_at->diffForHumans() }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="container-fluid">
        <div class="d-md-flex align-items-center justify-content-between mb-7">
            <h1 class="fs-5 m-0">Package Details</h1>
        </div>
        <div class="row">
            <div class="col-lg-12">
                <div class="table-responsive viewList">
                    <table id="showInsuranceAccountPayments" class="table table-striped">
                        <thead>
                            <tr class="text-start text-muted fw-bolder fs-7 text-uppercase gs-0">
                                <th class="text-center">#</th>
                                <th class="w-75">
                                    Package
                                </th>
                                
                            </tr>
                        </thead>
                        <tbody class="fw-bold">
                            @forelse($packages as $index => $package)
                                <tr>
                                    <td class="text-center w-5">{{ $loop->iteration }}</td>
                                    <td>
                                        {{ $package->package_name }}
                                    </td>
                                    
                                </tr>
                            @empty
                                <tr>
                                    <td class="text-center" colspan="3">{{ __('messages.no_data_available') }}</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
