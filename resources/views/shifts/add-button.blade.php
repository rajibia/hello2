<div class="d-flex justify-content-end">
    <a href="{{ route('duty.roster.shifts.excel') }}" class="btn btn-primary me-4" data-turbo="false">
        <i class="fas fa-file-excel"></i>
    </a>
    @modulePermission('shifts', 'add')
         <button class="btn btn-primary" type="submit" data-bs-toggle="modal" data-bs-target="#addShiftModal">
            {{ __('messages.shift.new_shift') }}
        </button>
    @endmodulePermission
</div>
