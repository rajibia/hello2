<div class="d-flex justify-content-end">
    <a href="" class="btn btn-primary me-4" data-turbo="false">
        <i class="fas fa-file-excel"></i>
    </a>

    @modulePermission('roster', 'add')
         <button class="btn btn-primary" type="submit" data-bs-toggle="modal" data-bs-target="#addRosterModal">
            {{ __('messages.roster.new_roster') }}
        </button>
    @endmodulePermission
</div>
