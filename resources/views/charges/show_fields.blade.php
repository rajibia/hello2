<div>
    <div class="tab-content" id="myTabContent">
        <div class="tab-pane fade show active" id="ChargesOverview" role="tabpanel">
            <div class="card mb-5 mb-xl-10">
                <div class="card-header border-0 pt-6">
                    <h3 class="card-title align-items-start flex-column">
                        <span class="card-label fw-bold fs-3 mb-1">{{ __('Charge Details') }}</span>
                        <span class="text-muted fw-semibold fs-6">Click on code or amount to copy</span>
                    </h3>
                    <div class="card-toolbar">
                        <button type="button" class="btn btn-sm btn-light-primary" onclick="location.reload()">
                            <i class="ki-duotone ki-arrows-circle fs-2"><span class="path1"></span><span class="path2"></span></i>
                            Refresh
                        </button>
                    </div>
                </div>

                <div class="card-body py-6">
                    <div class="row g-6 g-xl-9">

                        <!-- Charge Type -->
                        <div class="col-lg-4 col-md-6">
                            <label class="pb-2 fs-5 text-gray-600">{{ __('messages.charge_category.charge_type') }}:</label>
                            <div class="fs-5 text-gray-800 fw-semibold">
                                {{ $chargeTypes[$charge->charge_type] ?? 'N/A' }}
                            </div>
                        </div>

                        <!-- Charge Category -->
                        <div class="col-lg-4 col-md-6">
                            <label class="pb-2 fs-5 text-gray-600">{{ __('messages.charge.charge_category') }}:</label>
                            <div class="fs-5 text-gray-800 fw-semibold">
                                {{ $charge->chargeCategory->name ?? 'N/A' }}
                            </div>
                        </div>

                        <!-- Code - Click to Copy -->
                        <div class="col-lg-4 col-md-6">
                            <label class="pb-2 fs-5 text-gray-600">{{ __('messages.charge.code') }}:</label>
                            <div class="d-flex align-items-center">
                                <span class="badge badge-light-primary fs-5 fw-bold me-3 copy-text"
                                      data-copy="{{ $charge->code }}"
                                      style="cursor: pointer;" title="Click to copy">
                                    {{ $charge->code }}
                                </span>
                                <i class="ki-duotone ki-copy fs-2 text-success copy-icon d-none"></i>
                            </div>
                        </div>

                        <!-- Standard Charge - Click to Copy -->
                        <div class="col-lg-4 col-md-6">
                            <label class="pb-2 fs-5 text-gray-600">{{ __('messages.charge.standard_charge') }}:</label>
                            <div class="d-flex align-items-center">
                                <span class="fs-5 text-gray-800 fw-bold copy-text"
                                      data-copy="{{ $charge->standard_charge }} {{ $charge->currency_symbol ?? getCurrencySymbol() }}"
                                      style="cursor: pointer;" title="Click to copy amount">
                                    {{ checkNumberFormat($charge->standard_charge, strtoupper($charge->currency_symbol ?? getCurrentCurrency())) }}
                                </span>
                                <i class="ki-duotone ki-copy fs-2 text-success copy-icon ms-2 d-none"></i>
                            </div>
                        </div>

                        <!-- Created On -->
                        <div class="col-lg-4 col-md-6">
                            <label class="pb-2 fs-5 text-gray-600">{{ __('messages.common.created_on') }}:</label>
                            <div class="fs-5 text-gray-800 fw-semibold"
                                 title="{{ $charge->created_at->format('d M Y, h:i A') }}">
                                <i class="ki-duotone ki-calendar fs-6 me-2"></i>
                                {{ $charge->created_at->diffForHumans() }}
                            </div>
                        </div>

                        <!-- Updated At -->
                        <div class="col-lg-4 col-md-6">
                            <label class="pb-2 fs-5 text-gray-600">{{ __('messages.common.updated_at') }}:</label>
                            <div class="fs-5 text-gray-800 fw-semibold"
                                 title="{{ $charge->updated_at->format('d M Y, h:i A') }}">
                                <i class="ki-duotone ki-calendar fs-6 me-2"></i>
                                {{ $charge->updated_at->diffForHumans() }}
                            </div>
                        </div>

                        <!-- Description -->
                        <div class="col-12">
                            <label class="pb-2 fs-5 text-gray-600">{{ __('messages.common.description') }}:</label>
                            <div class="fs-5 text-gray-800">
                                @if(!empty($charge->description))
                                    <div class="text-justify">{!! nl2br(e($charge->description)) !!}</div>
                                @else
                                    <span class="text-muted italic">{{ __('messages.common.n/a') }}</span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<script>
document.addEventListener('DOMContentLoaded', function () {
    // Click to copy functionality
    document.querySelectorAll('.copy-text').forEach(element => {
        element.addEventListener('click', function () {
            const textToCopy = this.getAttribute('data-copy');
            const icon = this.parentElement.querySelector('.copy-icon');

            navigator.clipboard.writeText(textToCopy).then(() => {
                // Show checkmark
                icon.classList.remove('d-none');
                setTimeout(() => icon.classList.add('d-none'), 1500);

                // Optional: Toast notification (if you're using Toastr/SweetAlert)
                if (typeof toastr !== 'undefined') {
                    toastr.success('Copied: ' + textToCopy);
                }
            }).catch(() => {
                alert('Failed to copy!');
            });
        });
    });
});
</script>

<!-- Optional: Add nice toast style if you use Toastr -->
@push('scripts')
<script>
    toastr.options = {
        "closeButton": true,
        "progressBar": true,
        "positionClass": "toast-top-right",
        "timeOut": "2000"
    };
</script>
@endpush