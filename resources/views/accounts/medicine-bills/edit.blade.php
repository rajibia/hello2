@extends('layouts.app')
@section('title')
    {{ __('messages.medicine_bills.edit_medicine_bill') }}
@endsection
@section('header_toolbar')
    <div class="container-fluid">
        <div class="d-md-flex align-items-center justify-content-between mb-7">
            <h1 class="mb-0">@yield('title')</h1>
            <a href="{{ route('accounts-medicine-bills.index') }}" class="btn btn-outline-primary">{{ __('messages.common.back') }}</a>
        </div>
            </div>
    @endsection

@section('page_css')
<style>
    /* Style for disabled fields on edit page */
    .medicine-bill-container input[readonly],
    .medicine-bill-container select[disabled] {
        background-color: #f8f9fa !important;
        color: #6c757d !important;
        cursor: not-allowed;
        opacity: 0.8;
    }

    .medicine-bill-container select[disabled] {
        pointer-events: none;
    }

    /* Hide Select2 dropdown arrow for disabled selects */
    .medicine-bill-container select[disabled] + .select2-container .select2-selection__arrow {
        display: none;
    }

    /* Style for readonly fields */
    .medicine-bill-container input[readonly] {
        border-color: #dee2e6;
    }

    /* Add a subtle indicator that fields are read-only */
    .medicine-bill-container input[readonly]::placeholder,
    .medicine-bill-container select[disabled] option {
        color: #adb5bd;
    }
</style>
@endsection
@section('content')
    <div class="container-fluid">
        <div class="d-flex flex-column">
            <div class="row">
                <div class="col-12">
                    @include('layouts.errors')
                    @include('flash::message')
                    <div class="alert alert-danger d-none hide" id="validationErrorsBox"></div>

                    <!-- Information alert for edit page -->
                    <div class="alert alert-info alert-dismissible fade show" role="alert">
                        <i class="fas fa-info-circle me-2"></i>
                        <strong>{{ __('messages.common.note') }}:</strong>
                        {{ __('Medicine details cannot be modified on this page. Only payment information and notes can be updated.') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                </div>
            </div>
            <div class="card">
                <div class="card-body">
                    {{ Form::hidden('uniqueId', count($medicineBill->saleMedicine) + 1, ['id' => 'medicineUniqueId']) }}
                    {{ Form::hidden('associateMedicines', json_encode($medicineList), ['class' => 'associatePurchaseMedicines']) }}
                    {{ Form::hidden('medicineCategories', json_encode($medicineCategoriesList), ['id' => 'showMedicineCategoriesMedicineBill']) }}
                    {{ Form::hidden('medicine_bill_id', $medicineBill->id, ['id' => 'medicineBillId']) }}
                    {{ Form::hidden('payment-status', $medicineBill->payment_status, ['class' => 'payment-status']) }}
                    {{ Form::model($medicineBill, ['route' => ['accounts-medicine-bills.update', $medicineBill->id], 'method' => 'patch', 'id' => 'AccountMedicinebillForm']) }}
                    <div class="row">
                        @include('accounts.medicine-bills.medicine-table')
                    </div>
                    {{ Form::close() }}
                </div>
                @include('accounts.medicine-bills.templates.templates')
            </div>
        </div>
    @endsection
