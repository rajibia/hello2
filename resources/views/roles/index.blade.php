@extends('layouts.app')
@section('title')
    {{ __('messages.role-settings.roles') }}
@endsection
@section('content')
    <div class="container-fluid">
        <div class="d-flex flex-column">
            @include('flash::message')
            {{ Form::hidden('roleCreateUrl', route('role.store'), ['id' => 'roleCreateUrl']) }}
            {{ Form::hidden('roleUrl', url('roles'), ['id' => 'roleUrl']) }}
            {{ Form::hidden('role', __('messages.role-settings.role'), ['id' => 'role']) }}
            <livewire:role-table/>
            @include('roles.modal')
            @include('roles.edit_modal')
        </div>
    </div>
    <script src="{{ asset('assets/js/roles.js') }}"></script>
@endsection

