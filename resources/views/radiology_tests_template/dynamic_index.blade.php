@extends('layouts.app')
@section('title')
    {{ __('messages.dynamic_radiology_templates') }}
@endsection
@section('content')
    <div class="container-fluid">
        <div class="d-flex flex-column">
            @include('flash::message')

            <!-- Livewire Component -->
            @livewire('dynamic-radiology-template-table')
        </div>
    </div>
@endsection

@section('scripts')
<script>
    // Auto-hide flash messages
    setTimeout(function() {
        $('.alert').fadeOut('slow');
    }, 3000);
</script>
@endsection
