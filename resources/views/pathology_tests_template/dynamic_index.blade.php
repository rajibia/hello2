@extends('layouts.app')
@section('title')
    {{ __('Dynamic Pathology Templates') }}
@endsection
@section('content')
    <div class="container-fluid">
        <div class="d-flex flex-column">
            @include('flash::message')



            <!-- Livewire Component -->
            @livewire('dynamic-pathology-template-table')
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
