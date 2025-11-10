@extends('layouts.app')

@section('title', 'Create Dynamic Pathology Template')

@section('content')
    <div class="container-fluid">
        <div class="d-flex flex-column">
                    @include('flash::message')

            <!-- Livewire Component -->
            @livewire('create-dynamic-pathology-template')
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
