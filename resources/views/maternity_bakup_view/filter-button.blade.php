<div class="ms-auto">
    <div class="dropdown d-flex align-items-center me-4 me-md-5">
        <button class="btn btn btn-icon btn-primary text-white dropdown-toggle hide-arrow ps-2 pe-0" type="button"
                id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false">
            <i class='fas fa-filter'></i>
        </button>
        <div class="dropdown-menu py-0" aria-labelledby="dropdownMenuButton1">
            <div class="text-start border-bottom py-4 px-7">
                <h3 class="text-gray-900 mb-0">{{ __('messages.common.filter_options') }}</h3>
            </div>
            <div class="p-5">
                <a href="{{ route('maternity.index', ['filter' => 'upcoming']) }}"
                   class="dropdown-item {{ Request::query('filter') == 'upcoming' ? 'active' : '' }}">
                    {{ __('Upcoming Maternity') }}
                </a>
                <a href="{{ route('maternity.index') }}"
                   class="dropdown-item {{ !Request::has('filter') ? 'active' : '' }}">
                    {{ __('Maternity Today') }}
                </a>
                <a href="{{ route('maternity.index', ['filter' => 'old']) }}"
                   class="dropdown-item {{ Request::query('filter') == 'old' ? 'active' : '' }}">
                    {{ __('Old Maternity') }}
                </a>
            </div>
        </div>
    </div>
</div>
