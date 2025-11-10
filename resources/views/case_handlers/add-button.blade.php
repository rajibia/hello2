<div>
    <a href="{{ route('case.handler.excel') }}"
    class="btn btn-primary me-4"  data-turbo="false">
    <i class="fas fa-file-excel"></i>
	</a>
    @modulePermission('case-handlers', 'add')
    <a href="{{ route('case-handlers.create') }}" 
       class="btn btn-primary">{{ __('messages.case_handler.new_case_handler') }}</a>
    @endmodulePermission
</div>
