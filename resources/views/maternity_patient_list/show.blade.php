@extends('layouts.app')
@section('title')
    {{ __('messages.maternity_patient.maternity_patient_details') }}
@endsection
@section('header_toolbar')
    <div class="container-fluid">
        <div class="d-md-flex align-items-center justify-content-between mb-7">
            <h1 class="mb-0">@yield('title')</h1>
            <div class="text-end mt-4 mt-md-0">
                <a href="{{  url()->previous() }}"
                   class="btn btn-outline-primary ms-2">{{ __('messages.common.back') }}</a>
            </div>
        </div>
    </div>
@endsection
@section('content')
    <div class="container-fluid">
        <div class="d-flex flex-column">
            <div class="row">
                {{Form::hidden('visitedMaternityPatients',route('patient.maternity'),['id'=>'showVisitedMaternityPatientsList'])}}
                {{Form::hidden('patient_id',$maternity->patient_id,['id'=>'showVisitedMaternityPatientsListPatientId'])}}
                {{Form::hidden('maternityId',$maternity->id,['id'=>'showMaternityListPatientId'])}}
                {{Form::hidden('defaultDocumentImageUrl',asset('assets/img/default_image.jpg'),['id'=>'showMaternityListDefaultDocumentImageUrl'])}}
                <div class="col-12">
                    @include('flash::message')
                </div>
            </div>
            @include('maternity_patient_list.show_fields')
        </div>
    </div>
@endsection
@section('scripts')
    <script>
        $('ul.nav-tabs > li > a').on('shown.bs.tab', function (e) {
            var id = $(e.target).attr('href').substr(1);
            window.location.hash = id;
        });
    </script>
@endsection
