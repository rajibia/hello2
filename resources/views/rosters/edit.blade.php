@extends('layouts.app')

@section('content')
<div class="container">
    <div class="card shadow-sm">
        <div class="card-header bg-primary">
             <a href="{{ route('duty.roster.roster.index') }}" class="btn btn-light d-flex align-items-center me-3">
                <i class="fas fa-arrow-left me-2"></i>
                {{ __('messages.common.back') }}
            </a>
            <h1 class="mb-0 text-white">{{ __('messages.roster.edit_roster') }}</h1>
        </div>
        <div class="card-body">
            <form action="{{ route('duty.roster.roster.update', $roster->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="row">
                    <div class="col-md-6 form-group mb-3">
                        <label for="shift_id">{{ __('messages.shift.shift') }}</label>
                        <select class="form-select" id="shift_id" name="shift_id" required>
                            <option value="">{{ __('messages.shift.select_shift') }}</option>
                            @foreach ($shifts as $shift)
                                <option value="{{ $shift->id }}" 
                                    {{ $shift->id == $roster->shift_id ? 'selected' : '' }}>
                                    {{ $shift->shift_name }} 
                                    ({{ \Carbon\Carbon::parse($shift->shift_start)->format('g:i a') }} - {{ \Carbon\Carbon::parse($shift->shift_end)->format('g:i a') }})
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-6 form-group mb-3">
                        <label for="start_date">{{ __('messages.roster.start_date') }}</label>
                        <input type="date" name="start_date" id="start_date" 
                            value="{{ \Carbon\Carbon::parse($roster->start_date)->format('Y-m-d') }}" 
                            class="form-control" required>
                    </div>
                    <div class="col-md-6 form-group mb-3">
                        <label for="end_date">{{ __('messages.roster.end_date') }}</label>
                        <input type="date" name="end_date" id="end_date" 
                            value="{{ \Carbon\Carbon::parse($roster->end_date)->format('Y-m-d') }}" 
                            class="form-control" required>
                    </div>
                </div>
                <div class="d-flex justify-content-end mt-4">
                    <a href="{{ route('duty.roster.roster.index') }}" class="btn btn-secondary me-2">{{ __('messages.common.cancel') }}</a>
                    <button type="submit" class="btn btn-primary">{{ __('messages.common.save') }}</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
