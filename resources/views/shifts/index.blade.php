@extends('layouts.app')
@section('title')
    {{ __('messages.duty_roster.title') }}
@endsection
@section('content')
    <div class="container-fluid">
        @include('flash::message')
       
        <div class="d-flex flex-column">
            @include('shifts.add-shift-modal')
            {{Form::hidden('shiftsUrl',url('shifts'),['id'=>'indexshiftsUrl'])}}
            {{ Form::hidden('shifts', __('messages.duty_roster.shift'), ['id' => 'Shifts']) }} 
            <livewire:shifts-table/>
        </div>
        @include('shifts.templates.templates')
        {{-- @include('partials.page.teamplates.templates') --}}
    </div>
@endsection
