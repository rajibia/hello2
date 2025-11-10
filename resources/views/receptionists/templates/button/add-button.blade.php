<div>
    <a href="{{ route('receptionists.excel') }}"
    class="btn btn-primary me-4"  data-turbo="false">
    <i class="fas fa-file-excel"></i>
    </a>
	 @modulePermission('receptionists', 'add')
    <a href="{{ route('receptionists.create') }}"
       class="btn btn-primary">{{ __('messages.receptionist.new_receptionist') }}</a>
     @endmodulePermission
</div>
