@extends('layouts.app')
@section('title')
    {{ __('messages.patient.new_company') }}
@endsection
@section('header_toolbar')
    <div class="container-fluid">
        <div class="d-md-flex align-items-center justify-content-between mb-7">
            <h1 class="mb-0">@yield('title')</h1>
            <a href="{{ route('patients.index') }}"
               class="btn btn-outline-primary">{{ __('messages.common.back') }}</a>
        </div>
    </div>
@endsection
@section('content')
    <div class="container-fluid">
        <div class="d-flex flex-column">
            <div class="row">
                <div class="col-12">
                    @include('layouts.errors')
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
                </div>
            </div>
            <div class="card">
                <div class="card-body p-12">
                    <form action="{{url('companies/store')}}" method="post">
                        @csrf
                        <div class="row gx-10 mb-5">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="">Company Name</label>
                                    <input type="text" class="form-control" name="company_name">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="">Company Code</label>
                                    <input type="text" class="form-control" name="company_code">
                                </div>
                            </div>
                        </div>
                        <div class="d-flex justify-content-end">
                            <button class="btn btn-primary me-2" type="submit">
                                Save
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
