<div>
    <a href="{{ route('visitors.excel') }}"
    class="btn btn-primary me-4"  data-turbo="false">
    <i class="fas fa-file-excel"></i>
    </a>
	@modulePermission('visitors', 'add')
    <a href="{{ route('visitors.create') }}"
       class="btn btn-primary">{{ __('messages.visitor.new') }}</a
    
    @endmodulePermission   
></div>
