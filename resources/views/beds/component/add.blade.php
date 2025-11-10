<div class="dropdown">
    <button class="btn btn-primary" type="button"
            id="dropdownMenuButton1"
            data-bs-toggle="dropdown" aria-expanded="false">
        {{ __('messages.common.actions') }}
        <i class="fas fa-chevron-down"></i>
    </button>
    <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
        @modulePermission('beds', 'add')
        <li>
            <a href="javascript:void(0)" data-bs-toggle="modal" data-bs-target="#add_beds_modal"
               class="dropdown-item">{{ __('messages.bed.bed') }}</a>
        </li>
        @endmodulePermission
        @modulePermission('beds', 'add')
        <li>
            <a href="{{ route('create.bulk.beds') }}" class="dropdown-item">
                {{ __('messages.bed.new_bulk_bed') }}
            </a>
        </li>
        @endmodulePermission
        @modulePermission('beds', 'view')
            <li>
                <a href="{{ route('beds.excel') }}"
                   class="dropdown-item"
                   data-turbo="false">{{ __('messages.common.export_to_excel') }}</a>
            </li>
        @endmodulePermission
    </ul>
</div>
