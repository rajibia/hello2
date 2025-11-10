@extends('layouts.app')
@section('title')
    {{ __('messages.pathology_test.pathology_tests') }}
@endsection
@section('content')
    <div class="container-fluid">
        <div class="d-flex flex-column">
            @include('flash::message')

            <!-- Debug Information -->
            @if(config('app.debug'))
                <div class="alert alert-info">
                    <strong>Debug Info:</strong> Loading pathology-test-table component...
                </div>
            @endif

            @livewire('pathology-test-table')
        </div>
    </div>
@endsection
@section('scripts')
    <script>
        // Debug Livewire loading
        document.addEventListener('DOMContentLoaded', function() {
            console.log('DOM loaded, checking Livewire...');

            // Check if Livewire is available
            if (typeof Livewire !== 'undefined') {
                console.log('Livewire is available');
            } else {
                console.error('Livewire is not available');
            }
        });

        // Any additional JavaScript for pathology tests
    </script>
@endsection
