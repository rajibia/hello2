@extends('layouts.app')
@section('title')
   Nursing Progress Notes Details
@endsection
@section('header_toolbar')
    <div class="container-fluid">
        <div class="d-md-flex align-items-center justify-content-between mb-7">
            <h1 class="mb-0">Nurses Notes</h1>
            <div class="text-end mt-4 mt-md-0">
                {{-- <a class="btn btn-primary advance-payment-edit-btn"
                   data-id="{{ $general_examination->id }}">{{ __('messages.common.edit') }}</a> --}}
                <a href="#" onClick="history.back()"
                   class="btn btn-outline-primary ms-2">{{ __('messages.common.back') }}</a>
            </div>
        </div>
    </div>
@endsection
@section('content')
    <div class="container-fluid">
        <div class="d-flex flex-column livewire-table">
            <div class="row">
                <div class="col-12">
                    @include('flash::message')
                </div>
            </div>
                @include('nursing_progress_notes.show_fields')
        </div>
        @include('nursing_progress_notes.edit_modal')
    </div>
@endsection
@section('scripts')
    {{--   assets/js/custom/input_price_format.js --}}
    {{--   assets/js/general_examinations/general_examinations.js --}}
    {{--   assets/js/general_examinations/create-edit.js --}}
@endsection
