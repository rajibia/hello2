@extends('layouts.app')

@section('content')
<div class="container">
    <div class="card shadow-sm">
        <div class="card-header bg-primary d-flex align-items-center">
            <a href="{{ url('shifts') }}" class="btn btn-light me-3">
                <i class="fas fa-arrow-left"></i>
                {{ __('messages.common.back') }}
            </a>
            <h1 class="mb-0 text-white">{{ __('messages.shift.edit_shift') }}</h1>
        </div>
        <div class="card-body">
            <form action="{{ url('shifts/' . $shift->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="row">
                    <!-- Shift Name Field -->
                    <div class="col-md-6 form-group mb-3">
                        <label for="shift_name">{{ __('messages.shift.shift_name') }}</label>
                        <input type="text" name="shift_name" id="shift_name" 
                               value="{{ $shift->shift_name }}" class="form-control" required>
                    </div>

                    <!-- Shift Start Time Field -->
                    <div class="col-md-6 form-group mb-3">
                        <label for="shift_start">{{ __('messages.shift.shift_start') }}</label>
                        <input type="time" name="shift_start" id="shift_start" 
                               value="{{ \Carbon\Carbon::parse($shift->shift_start)->format('H:i') }}" 
                               class="form-control" required>
                    </div>

                    <!-- Shift End Time Field -->
                    <div class="col-md-6 form-group mb-3">
                        <label for="shift_end">{{ __('messages.shift.shift_end') }}</label>
                        <input type="time" name="shift_end" id="shift_end" 
                               value="{{ \Carbon\Carbon::parse($shift->shift_end)->format('H:i') }}" 
                               class="form-control" required>
                    </div>

                    <!-- Break Duration Field -->
                    <div class="col-md-6 form-group mb-3">
                        <label for="break_duration">{{ __('messages.shift.break_duration') }}</label>
                        <input type="number" name="break_duration" id="break_duration" 
                               value="{{ $shift->break_duration }}" class="form-control" required>
                    </div>
                </div>

                <div class="d-flex justify-content-end mt-4">
                    <a href="{{ url('shifts') }}" class="btn btn-secondary me-2">{{ __('messages.common.cancel') }}</a>
                    <button type="submit" class="btn btn-primary">{{ __('messages.common.save') }}</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
