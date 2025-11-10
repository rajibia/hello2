@extends('layouts.app')
@section('title')
    {{ __('messages.lab.lab_details') }}
@endsection
@section('header_toolbar')
    <div class="container-fluid">
        <div class="d-md-flex align-items-center justify-content-between mb-7">
            <h1 class="mb-0">@yield('title')</h1>
            <div class="text-end mt-4 mt-md-0">
                <a  class="btn btn-primary"
                    href="{{route('labs.edit',['lab' => $lab->id]) }}">{{ __('messages.common.edit') }}</a>
                <a href="{{ route('labs.index') }}"
                   class="btn btn-outline-primary ms-2">{{ __('messages.common.back') }}</a>
            </div>
        </div>
    </div>
    @include('document_types.edit_modal')
@endsection
@section('content')
    <div class="container-fluid">
        <div class="d-flex flex-column">
            <div class="row">
                <div class="col-12">
                    @include('flash::message')
                </div>
            </div>
                @include('labs.show_fields')
        </div>
    </div>
@endsection
