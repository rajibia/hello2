<div class="d-flex justify-content-end">
    <!-- Example Export Button -->
    <a href="" class="btn btn-primary me-4" data-turbo="false">
        <i class="fas fa-file-excel"></i>
    </a>

    <!-- Button to open the Add Bed Types Modal -->
    @modulePermission('bed-types', 'add')
        <button class="btn btn-primary" type="button" data-bs-toggle="modal" data-bs-target="#add_bed_types_modal">
            {{ __('messages.bed_type.new_bed_type') }}
        </button>
    @endmodulePermission
</div>
