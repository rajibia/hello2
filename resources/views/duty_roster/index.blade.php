@extends('layouts.app')
@section('title')
    {{ __('messages.duty_roster.title') }}
@endsection

@section('content')
    <div class="container-fluid">
        @include('flash::message')
        <div class="d-flex flex-column">
            <div class="card shadow-sm">
                <div class="card-body">
                    <livewire:duty-roster-table/>
                </div>
            </div>
        </div>
    </div>
@endsection
