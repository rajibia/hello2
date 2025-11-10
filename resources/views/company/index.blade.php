@extends('layouts.app')
@section('title')
    {{ __('messages.patient.new_company') }}
@endsection
@section('header_toolbar')
    <div class="container-fluid">
        <div class="d-md-flex align-items-center justify-content-between mb-7">
            <h1 class="mb-0">@yield('title')</h1>
            @modulePermission('companies', 'add')
            <a href="{{ route('companies.create') }}"
               class="btn btn-outline-primary">Create Company</a>
            @endmodulePermission
        </div>
    </div>
@endsection
@section('content')
    <div class="container-fluid">
        @include('flash::message')
        @if(session('success'))
            <div class="alert alert-success">
                <div>
                    <div class="d-flex">
                        <i class="fa-solid fa-face-grin-hearts me-5"></i>
                        <span class="mt-1 validationError">{{ session('success') }}</span>
                    </div>
                </div>
            </div>
        @endif
        <livewire:company-table />
    </div>
@endsection
