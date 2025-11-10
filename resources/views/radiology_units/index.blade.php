@extends('layouts.app')
@section('title')
    Radiology Units
@endsection
@section('content')
    @include('flash::message')
    <div class="container-fluid">
        <div class="d-flex flex-column">
            {{ Form::hidden('radiologyCategoryCreateUrl', route('radiology.unit.store'), ['id' => 'createRadiologyUnitURL']) }}
            {{ Form::hidden('radiologyUnitUrl', url('radiology-units'), ['id' => 'radiologyUnitURL']) }}
            {{ Form::hidden('radiologyUnitLang',__('messages.new_change.radiology_unit'), ['id' => 'radiologyUnitLang']) }}
            <livewire:radiology-unit-table/>
            @include('radiology_units.add_modal')
            @include('radiology_units.edit_modal')
        </div>
    </div>
@endsection
