<div class="modal fade" id="addShiftModal" tabindex="-1" aria-labelledby="addShiftModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addShiftModalLabel">{{ __('messages.shift.new_shift') }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="addShiftForm" action="{{ route('duty.roster.shifts.store') }}" method="POST">
                    <div class="mb-3">
                        <label for="shift_name" class="form-label">{{ __('messages.shift.shift_name') }}</label>
                        <input type="text" class="form-control" id="shift_name" name="shift_name" required>
                    </div>
                    <div class="mb-3">
                        <label for="shift_start" class="form-label">{{ __('messages.shift.shift_start') }}</label>
                        <input type="time" class="form-control" id="shift_start" name="shift_start" required>
                    </div>
                    <div class="mb-3">
                        <label for="shift_end" class="form-label">{{ __('messages.shift.shift_end') }}</label>
                        <input type="time" class="form-control" id="shift_end" name="shift_end" required>
                    </div>
                    <div class="mb-3">
                        <label for="break_duration" class="form-label">{{ __('messages.shift.break_duration') }}</label>
                        <input type="number" class="form-control" id="break_duration" name="break_duration" required>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('messages.common.cancel') }}</button>
                <button type="submit" form="addShiftForm" class="btn btn-primary">{{ __('messages.common.save') }}</button>
            </div>
        </div>
    </div>
</div>
