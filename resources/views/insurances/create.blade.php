@extends('layouts.app')
@section('title')
    {{ __('messages.insurance.new_insurance') }}
@endsection
@section('header_toolbar')
    <div class="container-fluid">
        <div class="d-md-flex align-items-center justify-content-between mb-7">
            <h1 class="mb-0">@yield('title')</h1>
            <a href="{{ route('insurances.index') }}"
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
                </div>
            </div>
            <div class="card">

                {{Form::hidden('insuranceSaveUrl',route('insurances.store'),['id'=>'createInsuranceSaveUrl','class'=>'insuranceSaveUrl'])}}
                {{Form::hidden('insuranceUrl',route('insurances.index'),['id'=>'createInsuranceUrl','class'=>'insuranceUrl'])}}
                {{Form::hidden('uniqueId',2,['id'=>'insuranceUniqueId','class'=>'insuranceUniqueId'])}}
                {{Form::hidden('discount',-1,['id'=>'insuranceDiscount','class'=>'insuranceDiscount'])}}
                {{Form::hidden('defaultAvatarImageUrl',asset('assets/img/avatar.png'),['class'=>'defaultAvatarImageUrl'])}}
                {{Form::hidden('isEdit',false,['class'=>'isEdit'])}}
                {{Form::hidden('downloadDocument_url',url('visitor-download'),['id'=>'userDownloadDocumentUrl'])}}
                <div class="card-body p-12">
                    {{ Form::open(['route' => 'insurances.store', 'files' => 'true', 'class'=>'insuranceForm', 'method'=>'POST']) }}

                    @include('insurances.fields')

                    {{ Form::close() }}
                </div>
            </div>
            @include('insurances.templates.templates')
        </div>
    </div>
@endsection
{{--
    JS File :-  assets/js/insurances/create-edit.js
                assets/js/custom/input_price_format.js
 --}}
