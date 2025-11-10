@extends('layouts.app')
@section('title')
    {{ __('messages.maternity_patients') }}
@endsection
@section('content')
    <div class="container-fluid">
        <div class="d-flex flex-column">
            @include('flash::message')
            {{Form::hidden('maternityPatientUrl',url('patient/my-maternity'),['id'=>'indexMaternityListPatientUrl'])}}
            <livewire:maternity-patient-department-table/>
        </div>
    </div>
@endsection
