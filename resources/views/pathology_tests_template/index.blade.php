@extends('layouts.app')
@section('title')
    {{ __('messages.pathology_tests') }}
@endsection
@section('content')
    <div class="container-fluid">
        <div class="d-flex flex-column">
            @include('flash::message')



            {{ Form::hidden('pathologyTestUrl', url('pathology-tests-templates'), ['id' => 'pathologyTestURL']) }}
            {{-- {{ Form::hidden('pathologyTestUrl', url('pathology-tests/templates/create'), ['id' => 'pathologyTestURL']) }} --}}
            {{ Form::hidden('pathology.test.show.modal', url('pathology-tests-templates/show-modal'), ['id' => 'pathologyTestShowUrl']) }}
            {{ Form::hidden('pathology-test-language', getCurrentLoginUserLanguageName(),['id' => 'pathologyTestLanguage']) }}
            {{ Form::hidden('pathology_test_templates', __('messages.pathology_test.pathology_tests'), ['id' => 'pathologyTest']) }}
            @livewire('pathology-tests-template-table')
            @include('partials.page.templates.templates')
            @include('pathology_tests_template.show_modal')
        </div>
    </div>
@endsection
{{-- JS File :- assets/js/pathology_tests/pathology_tests.js --}}
