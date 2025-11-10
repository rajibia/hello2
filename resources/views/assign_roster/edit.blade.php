@extends('layouts.app')

@section('content')
<div class="container">
    <div class="card shadow-sm">
        <div class="card-header bg-primary d-flex align-items-center">
            <a href="{{ route('duty.roster.assign.index') }}" class="btn btn-light me-3">
                <i class="fas fa-arrow-left"></i>
                {{ __('messages.common.back') }}
            </a>
            <h1 class="mb-0 text-white">{{ __('messages.assign_roster.edit_assign_roster') }}</h1>
        </div>
        <div class="card-body">
            <form action="{{ route('duty.roster.assign.update', $assignedRoster->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="row">
                    <!-- Roster Selection -->
                    <div class="col-md-6 form-group mb-3">
                        <label for="roster_id" class="form-label">{{ __('messages.assign_roster.roster_dates') }}</label>
                        <select class="form-select" id="roster_id" name="roster_id" required>
                            @foreach ($rosters as $roster)
                                <option value="{{ $roster->id }}" 
                                        {{ $roster->id == $assignedRoster->roster_id ? 'selected' : '' }}>
                                    ({{ \Carbon\Carbon::parse($roster->start_date)->format('m/d/Y') }} - {{ \Carbon\Carbon::parse($roster->end_date)->format('m/d/Y') }})
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Staff Selection -->
                    <div class="col-md-6 form-group mb-3">
                        <label for="user_id" class="form-label">{{ __('messages.assign_roster.user') }}</label>
                        <select class="form-select" id="user_id" name="user_id" required>
                            <optgroup label="Admins">
                                @foreach ($staffs as $staff)
                                    @if ($staff->owner_type === 'App\Models\admin')
                                        <option value="{{ $staff->id }}" 
                                                {{ $staff->id == $assignedRoster->user_id ? 'selected' : '' }}>
                                            {{ $staff->first_name }} {{ $staff->last_name }}
                                        </option>
                                    @endif
                                @endforeach
                            </optgroup>

                            <!-- Nurses Category -->
                            <optgroup label="Nurses">
                                @foreach ($staffs as $staff)
                                    @if ($staff->owner_type === 'App\Models\Nurse')
                                        <option value="{{ $staff->id }}">{{ $staff->first_name }} {{ $staff->last_name }}</option>
                                    @endif
                                @endforeach
                            </optgroup>

                            <!-- Accountants Category -->
                            <optgroup label="Accountants">
                                @foreach ($staffs as $staff)
                                    @if ($staff->owner_type === 'App\Models\Accountant')
                                        <option value="{{ $staff->id }}">{{ $staff->first_name }} {{ $staff->last_name }}</option>
                                    @endif
                                @endforeach
                            </optgroup>

                            <!-- Receptionists Category -->
                            <optgroup label="Receptionists">
                                @foreach ($staffs as $staff)
                                    @if ($staff->owner_type === 'App\Models\Receptionist')
                                        <option value="{{ $staff->id }}">{{ $staff->first_name }} {{ $staff->last_name }}</option>
                                    @endif
                                @endforeach
                            </optgroup>

                            <!-- Lab Technicians Category -->
                            <optgroup label="Lab Technicians">
                                @foreach ($staffs as $staff)
                                    @if ($staff->owner_type === 'App\Models\LabTechnician')
                                        <option value="{{ $staff->id }}">{{ $staff->first_name }} {{ $staff->last_name }}</option>
                                    @endif
                                @endforeach
                            </optgroup>

                            <!-- Pharmacists Category -->
                            <optgroup label="Pharmacists">
                                @foreach ($staffs as $staff)
                                    @if ($staff->owner_type === 'App\Models\Pharmacist')
                                        <option value="{{ $staff->id }}">{{ $staff->first_name }} {{ $staff->last_name }}</option>
                                    @endif
                                @endforeach
                            </optgroup>
                        </select>
                    </div>
                </div>

                <!-- Department Selection -->
                <div class="row">
                    <div class="col-md-6 form-group mb-3">
                        <label for="department_id" class="form-label">{{ __('messages.assign_roster.department_name') }}</label>
                        <select class="form-select" id="department_id" name="department_id" required>
                            @foreach ($departments as $department)
                                <option value="{{ $department->id }}" 
                                        {{ $department->id == $assignedRoster->department_id ? 'selected' : '' }}>
                                    {{ $department->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="d-flex justify-content-end mt-4">
                    <a href="{{ route('duty.roster.assign.index') }}" class="btn btn-secondary me-2">{{ __('messages.common.cancel') }}</a>
                    <button type="submit" class="btn btn-primary">{{ __('messages.common.save') }}</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
