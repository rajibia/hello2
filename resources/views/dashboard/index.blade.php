@extends('layouts.app')
@section('title')
    {{ __('messages.dashboard.dashboard') }}
@endsection
@section('page_css')
    {{--        <link rel="stylesheet" href="{{ asset('css/bootstrap-datetimepicker.css') }}"> --}}
    {{--        <link rel="stylesheet" href="{{ asset('assets/css/daterangepicker.css') }}"> --}}
@endsection
@section('css')
    {{--    <link rel="stylesheet" href="{{ asset('assets/css/detail-header.css') }}"> --}}
@endsection
<style>
    .widget-icon {
        background: var(--site-color) !important;
        border-color: var(--site-color) !important;
    }

    .mb-3 a {
        text-decoration: none !important;
    }
</style>
@section('content')
    <div class="container-fluid">
        <div class="d-flex flex-column">
            <div class="row">
                @if ($modules['Invoices'] == true)
                    <div class="col-md-6 mb-3">
                        <a href="{{ route('invoices.index') }}">
                            <div class="card card-animate bg-primary">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between">
                                        <div>
                                            <p class="fw-medium text-white mb-0">
                                                {{ __('messages.dashboard.total_invoices') }}</p>
                                            <h2 class="mt-4 ff-secondary fw-semibold text-white">{{ getCurrencySymbol() }}
                                                <span class="counter-value"
                                                    data-target="{{ formatCurrency($data['invoiceAmount']) }}">{{ formatCurrency($data['invoiceAmount']) }}</span>
                                            </h2>
                                        </div>
                                        <div>
                                            <div class="avatar-sm flex-shrink-0">
                                                <span class="avatar-title bg-info-subtle rounded-circle fs-2">
                                                    <i class="fa fa-file-invoice text-white"></i>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div><!-- end card body -->
                            </div> <!-- end card-->
                        </a>
                    </div> <!-- end col-->
                @endif
                @if ($modules['Payments'] == true)
                    <div class="col-md-6 mb-3">
                        <a href="{{ route('payments.index') }}">
                            <div class="card card-animate widget-icon">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between">
                                        <div>
                                            <p class="fw-medium text-black mb-0">
                                                {{ __('messages.dashboard.total_payments') }}</p>
                                            <h2 class="mt-4 ff-secondary fw-semibold">{{ getCurrencySymbol() }}
                                                <span class="counter-value"
                                                    data-target="{{ formatCurrency($data['paymentAmount']) }}">{{ formatCurrency($data['paymentAmount']) }}</span>
                                            </h2>
                                        </div>
                                        <div>
                                            <div class="avatar-sm flex-shrink-0">
                                                <span class="avatar-title bg-info-subtle rounded-circle fs-2">
                                                    <i class="fa fa-cedi-sign text-white"
                                                        style="color: rgba(var(--bs-info-rgb),var(--bs-text-opacity))!important;"></i>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div><!-- end card body -->
                            </div> <!-- end card-->
                        </a>
                    </div> <!-- end col-->
                @endif
            </div>
            <br>
            <div class="row">

                @if ($modules['Bills'])
                    <div class="col-xl-3 col-md-6 mb-3">
                        <!-- card -->
                        <a href="{{ route('bills.index') }}">
                            <div class="card card-animate">
                                <div class="card-body">
                                    <div class="d-flex align-items-center">
                                        <div class="flex-grow-1 overflow-hidden">
                                            <p class="text-uppercase fw-medium text-muted text-truncate mb-0">
                                                {{ __('messages.dashboard.total_bills') }}</p>
                                        </div>
                                    </div>
                                    <div class="d-flex align-items-end justify-content-between mt-4">
                                        <div>
                                            <h4 class="fs-22 fw-semibold ff-secondary mb-4">{{ getCurrencySymbol() }}
                                                <span class="counter-value"
                                                    data-target="{{ formatCurrency($data['billAmount']) }}">{{ formatCurrency($data['billAmount']) }}</span>
                                            </h4>
                                        </div>
                                    </div>
                                </div><!-- end card body -->
                            </div><!-- end card -->
                        </a>
                    </div><!-- end col -->
                @endif

                @if ($modules['Beds'])
                    <div class="col-xl-3 col-md-6 mb-3">
                        <!-- card -->
                        <a href="{{ route('beds.index') }}">
                            <div class="card card-animate">
                                <div class="card-body">
                                    <div class="d-flex align-items-center">
                                        <div class="flex-grow-1 overflow-hidden">
                                            <p class="text-uppercase fw-medium text-muted text-truncate mb-0">
                                                {{ __('messages.dashboard.available_beds') }}</p>
                                        </div>
                                    </div>
                                    <div class="d-flex align-items-end justify-content-between mt-4">
                                        <div>
                                            <h4 class="fs-22 fw-semibold ff-secondary mb-4"><span class="counter-value"
                                                    data-target="{{ $data['availableBeds'] }}">{{ $data['availableBeds'] }}</span>
                                            </h4>
                                        </div>
                                    </div>
                                </div><!-- end card body -->
                            </div><!-- end card -->
                        </a>
                    </div><!-- end col -->
                @endif
                @if ($modules['Advance Payments'])
                    <div class="col-md-6 mb-3">
                        <a href="{{ route('advanced-payments.index') }}">
                            <div class="card card-animate bg-primary">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between">
                                        <div>
                                            <p class="fw-medium text-white mb-0">
                                                {{ __('messages.dashboard.total_advance_payments') }}</p>
                                            <h2 class="mt-4 ff-secondary fw-semibold text-white">{{ getCurrencySymbol() }}
                                                <span class="counter-value"
                                                    data-target="{{ formatCurrency($data['billAmount']) }}">{{ formatCurrency($data['billAmount']) }}</span>
                                            </h2>
                                        </div>
                                        <div>
                                            <div class="avatar-sm flex-shrink-0">
                                                <span class="avatar-title bg-info-subtle rounded-circle fs-2">
                                                    <i class="fa fa-cedi-sign text-white"></i>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div><!-- end card body -->
                            </div> <!-- end card-->
                        </a>
                    </div> <!-- end col-->
                @endif

                @if ($modules['Patients'])
                    <div class="col-xl-3 col-md-6 mb-3">
                        <!-- card -->
                        <a href="{{ route('patients.index') }}">
                            <div class="card card-animate">
                                <div class="card-body">
                                    <div class="d-flex align-items-center">
                                        <div class="flex-grow-1 overflow-hidden">
                                            <p class="text-uppercase fw-medium text-muted text-truncate mb-0">
                                                {{ __('messages.dashboard.patients') }}</p>
                                        </div>
                                    </div>
                                    <div class="d-flex align-items-end justify-content-between mt-4">
                                        <div>
                                            <h4 class="fs-22 fw-semibold ff-secondary mb-4"><span class="counter-value"
                                                    data-target="{{ $data['patients'] }}">{{ $data['patients'] }}</span>
                                            </h4>
                                        </div>
                                    </div>
                                </div><!-- end card body -->
                            </div><!-- end card -->
                        </a>
                    </div><!-- end col -->
                @endif
                @if ($modules['Nurses'])
                    <div class="col-xl-3 col-md-6 mb-3">
                        <!-- card -->
                        <a href="{{ route('nurses.index') }}">
                            <div class="card card-animate">
                                <div class="card-body">
                                    <div class="d-flex align-items-center">
                                        <div class="flex-grow-1 overflow-hidden">
                                            <p class="text-uppercase fw-medium text-muted text-truncate mb-0">
                                                {{ __('messages.nurses') }}</p>
                                        </div>
                                    </div>
                                    <div class="d-flex align-items-end justify-content-between mt-4">
                                        <div>
                                            <h4 class="fs-22 fw-semibold ff-secondary mb-4"><span class="counter-value"
                                                    data-target="{{ $data['nurses'] }}">{{ $data['nurses'] }}</span> </h4>
                                        </div>
                                    </div>
                                </div><!-- end card body -->
                            </div><!-- end card -->
                        </a>
                    </div><!-- end col -->
                @endif
                @if ($modules['Doctors'])
                    <div class="col-md-6 mb-3">
                        <a href="{{ route('doctors.index') }}">
                            <div class="card card-animate widget-icon">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between">
                                        <div>
                                            <p class="fw-medium text-black mb-0">{{ __('messages.dashboard.doctors') }}</p>
                                            <h2 class="mt-4 ff-secondary fw-semibold text-black">
                                                <span class="counter-value"
                                                    data-target="{{ $data['doctors'] }}">{{ $data['doctors'] }}</span>
                                            </h2>
                                        </div>
                                        <div>
                                            <div class="avatar-sm flex-shrink-0">
                                                <span class="avatar-title bg-info-subtle rounded-circle fs-2">
                                                    <i class="fa fa-file-invoice text-black"
                                                        style="color: rgba(var(--bs-info-rgb),var(--bs-text-opacity))!important;"></i>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div><!-- end card body -->
                            </div> <!-- end card-->
                        </a>
                    </div> <!-- end col-->
                @endif

                @if ($modules['Accountants'])
                    <div class="col-xl-3 col-md-6 mb-3">
                        <!-- card -->
                        <a href="{{ route('accountants.index') }}">
                            <div class="card card-animate">
                                <div class="card-body">
                                    <div class="d-flex align-items-center">
                                        <div class="flex-grow-1 overflow-hidden">
                                            <p class="text-uppercase fw-medium text-muted text-truncate mb-0">
                                                {{ __('messages.accountants') }}</p>
                                        </div>
                                    </div>
                                    <div class="d-flex align-items-end justify-content-between mt-4">
                                        <div>
                                            <h4 class="fs-22 fw-semibold ff-secondary mb-4"><span class="counter-value"
                                                    data-target="{{ $data['accountants'] }}">{{ $data['accountants'] }}</span>
                                            </h4>
                                        </div>
                                    </div>
                                </div><!-- end card body -->
                            </div><!-- end card -->
                        </a>
                    </div><!-- end col -->
                @endif

                @if ($modules['Lab Technicians'])
                    <div class="col-xl-3 col-md-6 mb-3">
                        <!-- card -->
                        <a href="{{ route('lab-technicians.index') }}">
                            <div class="card card-animate">
                                <div class="card-body">
                                    <div class="d-flex align-items-center">
                                        <div class="flex-grow-1 overflow-hidden">
                                            <p class="text-uppercase fw-medium text-muted text-truncate mb-0">
                                                {{ __('messages.lab_technicians') }}</p>
                                        </div>
                                    </div>
                                    <div class="d-flex align-items-end justify-content-between mt-4">
                                        <div>
                                            <h4 class="fs-22 fw-semibold ff-secondary mb-4"><span class="counter-value"
                                                    data-target="{{ $data['labTechnicians'] }}">{{ $data['accountants'] }}</span>
                                            </h4>
                                        </div>
                                    </div>
                                </div><!-- end card body -->
                            </div><!-- end card -->
                        </a>
                    </div><!-- end col -->
                @endif
                @if ($modules['Admin'])
                    <div class="col-md-6 mb-3">
                        <a href="{{ route('admins.index') }}">
                            <div class="card card-animate bg-primary">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between">
                                        <div>
                                            <p class="fw-medium text-white mb-0">{{ __('messages.admin') }}</p>
                                            <h2 class="mt-4 ff-secondary fw-semibold text-white">
                                                <span class="counter-value"
                                                    data-target="{{ $data['admins'] }}">{{ $data['admins'] }}</span>
                                            </h2>
                                        </div>
                                        <div>
                                            <div class="avatar-sm flex-shrink-0">
                                                <span class="avatar-title bg-info-subtle rounded-circle fs-2">
                                                    <i class="fa fa-file-invoice text-white"></i>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div><!-- end card body -->
                            </div> <!-- end card-->
                        </a>
                    </div> <!-- end col-->
                @endif

                @if ($modules['Pharmacists'])
                    <div class="col-xl-3 col-md-6 mb-3">
                        <!-- card -->
                        <a href="{{ route('pharmacists.index') }}">
                            <div class="card card-animate">
                                <div class="card-body">
                                    <div class="d-flex align-items-center">
                                        <div class="flex-grow-1 overflow-hidden">
                                            <p class="text-uppercase fw-medium text-muted text-truncate mb-0">
                                                {{ __('messages.pharmacists') }}</p>
                                        </div>
                                    </div>
                                    <div class="d-flex align-items-end justify-content-between mt-4">
                                        <div>
                                            <h4 class="fs-22 fw-semibold ff-secondary mb-4"><span class="counter-value"
                                                    data-target="{{ $data['pharmacists'] }}">{{ $data['pharmacists'] }}</span>
                                            </h4>
                                        </div>
                                    </div>
                                </div><!-- end card body -->
                            </div><!-- end card -->
                        </a>
                    </div><!-- end col -->
                @endif
                @if ($modules['Receptionists'])
                    <div class="col-xl-3 col-md-6 mb-3">
                        <!-- card -->
                        <a href="{{ route('receptionists.index') }}">
                            <div class="card card-animate">
                                <div class="card-body">
                                    <div class="d-flex align-items-center">
                                        <div class="flex-grow-1 overflow-hidden">
                                            <p class="text-uppercase fw-medium text-muted text-truncate mb-0">
                                                {{ __('messages.receptionists') }}</p>
                                        </div>
                                    </div>
                                    <div class="d-flex align-items-end justify-content-between mt-4">
                                        <div>
                                            <h4 class="fs-22 fw-semibold ff-secondary mb-4"><span class="counter-value"
                                                    data-target="{{ $data['receptionists'] }}">{{ $data['receptionists'] }}</span>
                                            </h4>
                                        </div>
                                    </div>
                                </div><!-- end card body -->
                            </div><!-- end card -->
                        </a>
                    </div><!-- end col -->
                @endif
                @if ($modules['Today Payment Reports'])
                    <div class="col-md-6 mb-3">
                        <a href="{{ route('today-payment-reports.index') }}">
                            <div class="card card-animate widget-icon">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between">
                                        <div>
                                            <p class="fw-medium text-black mb-0">Payments Received Today</p>
                                            <h2 class="mt-4 ff-secondary fw-semibold text-black">
                                                <span class="counter-value"
                                                    data-target="{{ formatCurrency($data['todayPaidAmount']) }}">{{ formatCurrency($data['todayPaidAmount']) }}</span>
                                            </h2>
                                        </div>
                                        <div>
                                            <div class="avatar-sm flex-shrink-0">
                                                <span class="avatar-title bg-info-subtle rounded-circle fs-2">
                                                    <i class="fa fa-file-invoice text-black"
                                                        style="color: rgba(var(--bs-info-rgb),var(--bs-text-opacity))!important;"></i>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div><!-- end card body -->
                            </div> <!-- end card-->
                        </a>
                    </div> <!-- end col-->
                @endif
            </div>
            <div class="row">
                <div class="col-9">
                    <div class="card">
                        <div class="card-body">
                            {{--                            <h4 class="float-end">{{ \Carbon\Carbon::now()->year }}</h4> --}}
                            <canvas id="incomeExpenseChart"></canvas>
                        </div>
                    </div>
                </div>
                <div class="col-3">
                    <div class="card overflow-auto my-5">
                        <div class="card-header pb-0 px-10 my-2">
                            <h3 class="mb-0">
                                {{ __('messages.dashboard.notice_boards') }}
                            </h3>
                        </div>
                        <div class="card-body pt-7 pb-2">
                            @if (count($data['noticeBoards']) > 0)
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th scope="col">{{ __('messages.dashboard.title') }}</th>
                                            {{-- <th scope="col" class="text-center">
                                                {{ __('messages.common.created_on') }}
                                            </th> --}}
                                        </tr>
                                    </thead>
                                    <tbody class="text-gray-600 fw-bold">
                                        @foreach ($data['noticeBoards'] as $noticeBoard)
                                            <tr>
                                                <td>
                                                    <a href="javascript:void(0)" data-id="{{ $noticeBoard->id }}"
                                                        class="text-decoration-none notice-board-view-btn">{{ Str::limit($noticeBoard->title, 24, '...') }}</a>
                                                </td>
                                                {{-- <td class="text-center">
                                                    <span class="badge bg-light-info">
                                                        {{ \Carbon\Carbon::parse($noticeBoard->created_at)->translatedFormat('jS M, Y') }}
                                                    </span>
                                                </td> --}}
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            @else
                                <h2 class="mb-0 text-center fs-2">{{ __('messages.dashboard.no_notice_yet') }}... </h2>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <div class="row my-2">
                <div class="col-xxl-5 col-12 mb-7 mb-xxl-0">
                    <div class="card overflow-auto">
                        <div class="card-header pb-0 px-10">
                            <h3 class="mb-0">
                                {{ __('messages.enquiries') }}
                            </h3>
                        </div>
                        <div class="card-body pt-7">
                            @if (count($data['enquiries']) > 0)
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th scope="col">{{ __('messages.enquiry.name') }}</th>
                                            <th scope="col">{{ __('messages.enquiry.email') }}</th>
                                            <th scope="col" class="text-center text-muted">
                                                {{ __('messages.common.created_on') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody class="text-gray-600 fw-bold">
                                        @foreach ($data['enquiries'] as $enquiry)
                                            <tr>
                                                <td>
                                                    <a href="{{ route('enquiry.show', $enquiry->id) }}"
                                                        class="text-primary-800 text-decoration-none mb-1 fs-6">{{ Str::limit($enquiry->full_name, 10, '...') }}</a>
                                                </td>
                                                <td class="text-start">
                                                    <span class="text-muted fw-bold d-block">{{ $enquiry->email }}</span>
                                                </td>
                                                <td class="text-center text-muted fw-bold">
                                                    <span class="badge bg-light-info">
                                                        {{ \Carbon\Carbon::parse($enquiry->created_at)->translatedFormat('jS M, Y') }}
                                                    </span>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            @else
                                <h4 class="mb-0 text-center fs-2">{{ __('messages.dashboard.no_enquiries_yet') }}</h4>
                            @endif
                        </div>
                    </div>
                </div>

                {{-- Appointment table --}}
                <div class="col-xxl-7 col-12 mb-7 mb-xxl-0">
                    <div class="card overflow-auto">
                        <div class="card-header pb-0 px-10">
                            <h3 class="mb-0">
                                {{ __('messages.appointments') }}
                            </h3>
                        </div>
                        <div class="card-body pt-7">
                            <livewire:dashboard-appointment-table>
                        </div>
                    </div>
                </div>
                {{-- end Appointment table --}}
            </div>
            {{--             Income & Expense Chart --}}
            {{--                        <div class="row"> --}}
            {{--                            <div class="col-lg-12"> --}}
            {{--                                <div class="card"> --}}
            {{--                                    <div class="card-body"> --}}
            {{--                                        <div class="row justify-content-between"> --}}
            {{--                                            <div class="col-sm-6 col-md-6 col-lg-6 pt-2"> --}}
            {{--                                                <h5>{{ __('messages.dashboard.income_and_expense_report') }}</h5> --}}
            {{--                                            </div> --}}
            {{--                                            <div class="col-md-3"> --}}
            {{--                                                <div id="time_range" class="time_range d-flex"> --}}
            {{--                                                    <i class="far fa-calendar-alt" --}}
            {{--                                                       aria-hidden="true"></i>&nbsp;&nbsp;<span></span> --}}
            {{--                                                    <b class="caret"></b> --}}
            {{--                                                </div> --}}
            {{--                                            </div> --}}
            {{--                                        </div> --}}
            {{--                                        <div class="table-responsive-sm"> --}}
            {{--                                            <div class="pt-2"> --}}
            {{--                                                <canvas id="daily-work-report" class="mh-400px"></canvas> --}}
            {{--                                            </div> --}}
            {{--                                        </div> --}}
            {{--                                    </div> --}}
            {{--                                </div> --}}
            {{--                            </div> --}}
            {{--                        </div> --}}

            {{ Form::hidden('incomeExpenseReportUrl', route('income-expense-report'), ['id' => 'dashboardIncomeExpenseReportUrl', 'class' => 'incomeExpenseReportUrl']) }}
            {{ Form::hidden('currentCurrencyName', getCurrencySymbol(), ['id' => 'dashboardCurrentCurrencyName', 'class' => 'currentCurrencyName']) }}
            {{--                        {{Form::hidden('currencies',json_encode($data['currency']),['id'=>'createBillDate','class'=>'currencies'])}} --}}
            {{ Form::hidden('income_and_expense_reports', __('messages.dashboard.income_and_expense_reports'), ['id' => 'dashboardIncome_and_expense_reports', 'class' => 'income_and_expense_reports']) }}
            {{ Form::hidden('defaultAvatarImageUrl', asset('assets/img/avatar.png'), ['id' => 'dashboardDefaultAvatarImageUrl', 'class' => 'defaultAvatarImageUrl']) }}
            {{ Form::hidden('noticeBoardUrl', url('notice-boards'), ['id' => 'dashboardNoticeBoardUrl', 'class' => 'noticeBoardUrl']) }}
            {{ Form::hidden('dashboardChart', route('dashboard.chart'), ['id' => 'dashboardChart', 'class' => 'dashboardChart']) }}

        </div>
        @include('employees.notice_boards.show_modal')
    </div>
@endsection

@section('scripts')
    <script src="{{ mix('js/pages.js') }}"></script>
@endsection
