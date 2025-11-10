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
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        <h3>Edit Company</h3>
        <form action="{{ route('companies.update', $company->id) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="mb-3">
                <label>Name</label>
                <input type="text" name="name" class="form-control" value="{{ $company->name }}" required>
            </div>
            <div class="mb-3">
                <label>Code</label>
                <input type="text" name="code" class="form-control" value="{{ $company->code }}" required>
            </div>
            <div class="mb-3">
                <label>Status</label>
                <select name="is_active" class="form-control">
                    <option value="1" {{ $company->is_active ? 'selected' : '' }}>Active</option>
                    <option value="0" {{ !$company->is_active ? 'selected' : '' }}>Inactive</option>
                </select>
            </div>
            <button class="btn btn-primary">Update</button>
        </form>
    </div>
@endsection
