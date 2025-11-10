<div class="ms-0 ms-md-2">
    <div class="dropdown d-flex align-items-center me-4 me-md-5">
        <button
                class="btn btn btn-icon btn-primary text-white dropdown-toggle hide-arrow ps-2 pe-0"
                type="button" data-bs-auto-close="outside"
                data-bs-toggle="dropdown" aria-expanded="false"
                id="ipdDateFilterBtn">
            <i class='fas fa-calendar me-2'></i>{{ __('messages.common.filter_by_date') }}
        </button>
        <div class="dropdown-menu py-0" aria-labelledby="ipdDateFilterBtn">
            <div class="text-start border-bottom py-4 px-7">
                <h3 class="text-gray-900 mb-0">{{ __('messages.common.filter_by_date') }}</h3>
            </div>
            <div class="p-5">
                <input class="form-control form-control-solid" placeholder="Select Date Range" id="ipdDateFilter"/>
            </div>
        </div>
    </div>
</div>
