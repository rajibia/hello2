@extends('layouts.app')
@section('title')
    {{ __('messages.roster.roster') }}
@endsection
@section('content')
    <div class="container-fluid">
        @include('flash::message')
       
        <div class="d-flex flex-column">
             @include('rosters.add-roster-modal')
             {{Form::hidden('rosterUrl',url('roster'),['id'=>'indexRosterUrl'])}}
            {{ Form::hidden('roster', __('messages.roster.roster'), ['id' => 'Rosters']) }} 
            <livewire:roster-table/>
        </div>
        @include('rosters.templates.templates')
        @include('partials.page.templates.templates')
    </div>
@endsection