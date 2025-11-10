@extends('layouts.app')
@section('title')
    {{ __('messages.role-settings.roles') }}
@endsection
@section('content')
    <div class="container-fluid">
        <div class="d-flex flex-column">
            <!--@include('flash::message')-->
            {{ Form::hidden('roleCreateUrl', route('role.store'), ['id' => 'roleCreateUrl']) }}
            {{ Form::hidden('roleUrl', url('roles'), ['id' => 'roleUrl']) }}
            {{ Form::hidden('operationCategory', __('messages.operation_category.operation_category'), ['id' => 'operationCategory']) }}
            <h3 class="text-center">Modules Assign To Role &raquo; <span class="badge bg-success ">{{$role->name}}</span></h3>
            <livewire:assign-module-table :role="$role"/>
            @include('roles.modal')
            @include('roles.edit_modal')
        </div>
    </div>
    <script src="{{ asset('assets/js/roles.js') }}"></script>
   <script>
    window.addEventListener('permission-updated', event => {
        const message = event.detail.message;
        displaySuccessMessage(message);
    });
</script>
@endsection

