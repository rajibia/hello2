@extends('layouts.app')

@section('header_toolbar')
    <div class="container-fluid">
        <div class="d-md-flex align-items-center justify-content-between mb-7">
            <h1 class="mb-0">Add Previous Obstetric History</h1>
            <a href="javascript:;" onclick="history.back()" class="btn btn-outline-primary">{{ __('messages.common.back') }}</a>
        </div>
    </div>
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                @include('layouts.errors')
            </div>
        </div>
        <div class="row">
            @include('maternity.obstetric.fields')
        </div>
    </div>
@endsection
