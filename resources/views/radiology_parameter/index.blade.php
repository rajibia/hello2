@extends('layouts.app')
@section('title')
    {{ __('messages.new_change.radiology_parameters') }}
@endsection
@section('content')
    @include('flash::message')
    <div class="container-fluid">
        <div class="d-flex flex-column">
            {{ Form::hidden('radiologyParameterCreateUrl', route('radiology.parameter.store'), ['id' => 'createPathologyParameterURL']) }}
            {{ Form::hidden('radiologyParameterUrl', url('radiology-parameters'), ['id' => 'radiologyParameterURL']) }}
            {{ Form::hidden('radiologyParameterLang',__('messages.new_change.radiology_parameter'), ['id' => 'radiologyParameterLang']) }}
            <livewire:radiology-parameter-table/>
            @include('radiology_parameter.modal')
            @include('radiology_parameter.edit_modal')
        </div>
    </div>
@endsection
