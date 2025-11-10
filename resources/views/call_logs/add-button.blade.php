<div>
    <a href="{{ route('call_logs.excel') }}"
    class="btn btn-primary me-4"  data-turbo="false">
    <i class="fas fa-file-excel"></i>
    </a>
	@modulePermission('call-logs', 'add')
    <a href="{{ route('call_logs.create') }}"
    class="btn btn-primary  px-5">{{ __('messages.call_log.new') }}
    </a>
    @endmodulePermission
</div>
