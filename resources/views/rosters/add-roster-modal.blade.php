<div class="modal fade" id="addRosterModal" tabindex="-1" aria-labelledby="addRosterModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addRosterModalLabel">{{ __('messages.roster.new_roster') }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="addRosterForm" action="{{ route('duty.roster.roster.store') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                       <label for="shift_id" class="form-label">{{ __('messages.shift.shift_name') }}</label>
                        <select class="form-select" id="shift_id" name="shift_id" required>
                            <option value="">{{ __('messages.shift.select_shift') }}</option>
                            @foreach ($shifts as $shift)
                                <option value="{{ $shift->id }}">
                                    {{ $shift->shift_name }} 
                                    ({{ \Carbon\Carbon::parse($shift->shift_start)->format('g:i a') }} - {{ \Carbon\Carbon::parse($shift->shift_end)->format('g:i a') }})
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="start_date" class="form-label">{{ __('messages.roster.start_date') }}</label>
                        <input type="date" class="form-control" id="start_date" name="start_date" required>
                    </div>
                    <div class="mb-3">
                        <label for="end_date" class="form-label">{{ __('messages.roster.end_date') }}</label>
                        <input type="date" class="form-control" id="end_date" name="end_date" required>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('messages.common.cancel') }}</button>
                <button type="submit" form="addRosterForm" class="btn btn-primary">{{ __('messages.common.save') }}</button>
            </div>
        </div>
    </div>
</div>
