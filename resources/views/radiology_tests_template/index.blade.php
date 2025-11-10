@extends('layouts.app')
@section('title')
    {{ __('messages.radiology_tests') }}
@endsection
@section('content')
    <div class="container-fluid">
        <div class="d-flex flex-column text-center">
            @include('flash::message')
            {{ Form::hidden('radiologyTestUrl', url('radiology-tests-templates'), ['id' => 'radiologyTestURL']) }}
            {{-- {{ Form::hidden('radiologyTestUrl', url('radiology-tests/templates/create'), ['id' => 'radiologyTestURL']) }} --}}
            {{ Form::hidden('radiology.test.show.modal', url('radiology-tests-templates/show-modal'), ['id' => 'radiologyTestShowUrl']) }}
            {{ Form::hidden('radiology-test-language', getCurrentLoginUserLanguageName(),['id' => 'radiologyTestLanguage']) }}
            {{ Form::hidden('radiology_test_templates', __('messages.radiology_test.radiology_tests'), ['id' => 'radiologyTest']) }}
            {{-- @livewire('radiology-tests-template-table') --}}
            <livewire:radiology-tests-template-table/>
            {{-- @include('radiology_tests_template.table') --}}
            @include('partials.page.templates.templates')
            @include('radiology_tests_template.show_modal')
        </div>
    </div>
@endsection
{{-- JS File :- assets/js/radiology_tests/radiology_tests.js --}}
