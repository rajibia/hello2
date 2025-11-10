@extends('layouts.app')
@section('title')
    {{__('messages.assign_roster.title')}}
@endsection
@section('content')
    <div class="container-fluid">
        <div class="d-flex flex-column">
            @include('flash::message')
            {{ Form::hidden('assignRosterUrl', url('assign-roster'), ['id' => 'assignRosterUrl']) }}
            {{ Form::hidden('assign-roster', __('messages.assign_roster.name'), ['id' => 'AssignRoster']) }}
            <livewire:assign-roster-table-component />

            @include('assign_roster.assign-roster-modal')
            {{-- @include('assign_roster.templates.templates') --}}
            @include('partials.page.templates.templates')
        </div>
    </div>
@endsection
@section('scripts')
    {{--  assets/js/accountants/accountants.js --}}
@endsection
