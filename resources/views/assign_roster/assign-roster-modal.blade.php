<div class="modal fade" id="assignRosterModal" tabindex="-1" aria-labelledby="assignRosterModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="assignRosterModalLabel">{{ __('messages.assign_roster.new_assign_roster') }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="{{ route('duty.roster.assign.store') }}" method="POST">
                    @csrf
                    <div class="row mb-3">
                        <!-- Roster Selection -->
                        <div class="col-md-6">
                        <label for="roster_id" class="form-label">{{ __('messages.assign_roster.roster_dates') }}</label>
                        <select class="form-select" id="roster_id" name="roster_id" required>
                            <option value="">{{ __('messages.roster.select_roster') }}</option>
                            @foreach ($rosters as $roster)
                                <option value="{{ $roster->id }}">
                                    ({{ \Carbon\Carbon::parse($roster->start_date)->format('m/d/Y') }} - {{ \Carbon\Carbon::parse($roster->end_date)->format('m/d/Y') }})
                                </option>
                            @endforeach
                        </select>
                    </div>
                        <!-- Staff Selection -->
                        <div class="col-md-6">
                            <label for="user_id" class="form-label">{{ __('messages.assign_roster.user') }}</label>
                            <select class="form-select" id="user_id" name="user_id" required>
                                <option value="">{{ __('messages.assign_roster.select_staff') }}</option>

                                <!-- Admins Category -->
                                <optgroup label="Admins">
                                    @foreach ($staffs as $staff)
                                        @if ($staff->owner_type === 'App\Models\admin')
                                            <option value="{{ $staff->id }}">{{ $staff->first_name }} {{ $staff->last_name }}</option>
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

                    <!-- Department Name Input -->
                    <div class="row mb-3">
                        <div class="col-12">
                            <label for="department_id" class="form-label">{{ __('messages.assign_roster.department_name') }}</label>
                            <select class="form-select" id="department_id" name="department_id" required>
                            <option value="">{{ __('messages.assign_roster.select_department') }}</option>
                            @foreach ($departments as $department)
                                <option value="{{ $department->id }}">
                                   {{ $department->name }}
                                </option>
                            @endforeach
                        </select>
                        </div>
                    </div>
                    
                    <hr>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('messages.common.cancel') }}</button>
                        <button type="submit" class="btn btn-primary">{{ __('messages.common.save') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
