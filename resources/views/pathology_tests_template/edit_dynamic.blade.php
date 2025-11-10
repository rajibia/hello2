@extends('layouts.app')
@section('title')
    {{ __('Edit Dynamic Pathology Template') }}
@endsection
@section('content')
    <div class="container-fluid">
        <div class="d-flex flex-column">
            @include('flash::message')

            <div class="card">
                <div class="card-header border-0">
                    <div class="row w-100">
                        <div class="col-8">
                            <h3 class="mb-0">{{ __('Edit Dynamic Pathology Template') }}</h3>
                        </div>
                        <div class="col-4 text-right">
                            <a href="{{ route('pathology.test.template.index') }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-left"></i> {{ __('Back to Templates') }}
                            </a>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <!-- Livewire Component -->
                    @livewire('edit-dynamic-pathology-template', ['templateId' => $id])
                </div>
            </div>
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
