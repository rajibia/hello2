@extends('layouts.app')
@section('title')
    {{ __('Company Claim Detail') }}
@endsection
@section('content')
    <div class="container-fluid">
        <livewire:company-claim-detail :company="$company" />
    </div>
@endsection
