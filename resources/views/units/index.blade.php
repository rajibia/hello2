@extends('layouts.app')
@section('title')
    {{ __('messages.unit.units') }}
@endsection
@section('content')
    <div class="container-fluid">
        <div class="d-flex flex-column">
            @include('flash::message')
            {{Form::hidden('unitCreateUrl',route('units.store'),['id'=>'indexUnitCreateUrl'])}}
            {{Form::hidden('unitsUrl',url('units'),['id'=>'indexUnitsUrl'])}}
            {{ Form::hidden('unit', __('messages.unit.unit'), ['id' => 'localUnit']) }}
            <livewire:unit-table/>
            @include('units.create_modal')
            @include('units.edit_modal')
            @include('partials.modal.templates.templates')
        </div>
    </div>
@endsection
@section('scripts')
    <script src="{{mix('js/pages.js')}}"></script>
@endsection
