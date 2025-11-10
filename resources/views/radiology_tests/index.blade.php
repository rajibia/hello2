@extends('layouts.app')
@section('title')
    {{ __('messages.radiology_tests') }}
@endsection
@section('content')
    <div class="container-fluid">
        <div class="d-flex flex-column ">
            @include('flash::message')
            {{ Form::hidden('radiologyTestUrl', url('radiology-tests'), ['id' => 'radiologyTestURL']) }}
            {{ Form::hidden('radiology.test.show.modal', url('radiology-tests-show-modal'), ['id' => 'radiologyTestBillShowUrl']) }}
            {{ Form::hidden('radiology.test.item.show.modal', url('radiology-tests-items-show-modal'), ['id' => 'radiologyTestItemShowUrl']) }}
            {{ Form::hidden('radiology.test.item.result.show.modal', url('radiology-tests-items-result-show-modal'), ['id' => 'radiologyTestItemResultShowUrl']) }}
            {{ Form::hidden('radiology.test.item.collection', url('radiology-tests-items-collection'), ['id' => 'radiologyTestCollectionUrl']) }}
            {{ Form::hidden('radiology.test.item.result', url('radiology-tests-items-result'), ['id' => 'radiologyTestResultUrl']) }}
            {{ Form::hidden('radiology-test-language', getCurrentLoginUserLanguageName(),['id' => 'radiologyTestLanguage']) }}
            {{ Form::hidden('radiology_test', __('messages.radiology_test.radiology_tests'), ['id' => 'radiologyTest']) }}
            <livewire:radiology-test-table/>
            @include('partials.page.templates.templates')
            @include('radiology_tests.show_modal')
            @include('radiology_tests.show_modal_collection')
            @include('radiology_tests.show_modal_result')
        </div>
    </div>
@endsection
{{-- JS File :- assets/js/radiology_tests/radiology_tests.js --}}
