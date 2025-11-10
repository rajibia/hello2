@extends('layouts.app')
@section('title')
    {{ __('messages.permission-settings.permissions') }}
@endsection
@section('content')
<div class="container-fluid">
  <div class="d-flex flex-column"> @include('flash::message')
    {{ Form::hidden('permissionCreateUrl', route('permissions.store'), ['id' => 'permissionCreateUrl']) }}
    {{ Form::hidden('permissionUrl', url('permissions'), ['id' => 'permissionUrl']) }}
    {{ Form::hidden('operationCategory', __('messages.permission-settings.permission'), ['id' => 'operationCategory']) }}
    
     <livewire:permission-table/>
    @include('permissions.modal')
    @include('permissions.edit_modal')
    
  </div>
</div>
<script src="{{ asset('assets/js/permissions.js') }}"></script>
@endsection 