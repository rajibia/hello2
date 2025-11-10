@extends('layouts.app')
@section('title')
    {{ __('messages.store.stores') }}
@endsection
@section('content')
    <div class="container-fluid">
        <div class="d-flex flex-column">
            @include('flash::message')
            {{Form::hidden('storeCreateUrl',route('stores.store'),['id'=>'indexStoreCreateUrl'])}}
            {{Form::hidden('storesUrl',url('stores'),['id'=>'indexStoresUrl'])}}
            {{ Form::hidden('store', __('messages.store.store'), ['id' => 'localStore']) }}
            <livewire:store-table/>
            @include('stores.create_modal')
            @include('stores.edit_modal')
            @include('partials.modal.templates.templates')
        </div>
    </div>
@endsection
@section('scripts')
    <script src="{{mix('js/pages.js')}}"></script>
@endsection
