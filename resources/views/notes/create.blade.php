@extends('layouts.app')
@section('title')
    Nursing Progress Notes
@endsection
@section('header_toolbar')
    <div class="container-fluid">
        <div class="d-md-flex align-items-center justify-content-between mb-7">
            <h1 class="mb-0">@yield('title')</h1>
            <a href="javascript;" onClick="history.back()"
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
                    @include('flash::message')
                </div>
            </div>
            {{ Form::open(['route' => 'nursing_progress_notes.store', 'id' => 'createVitals']) }}
            @csrf
            <div class="card">
                <div class="card-body">
                    @include('nursing_progress_notes.fields')
                </div>
            </div>
            {{ Form::close() }}
       </div>
    </div>
@endsection
@section('scripts')
    {{--  assets/js/prescriptions/create-edit.js --}}
@endsection
