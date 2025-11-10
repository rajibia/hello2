<div>
        {{-- <a href="{{ route('supplier.excel') }}"
           class="btn btn-primary me-4"    data-tirbo='false'>
           <i class="fas fa-file-excel"></i>
        </a> --}}
         @modulePermission('suppliers', 'add')
        <a href="{{ route('suppliers.create') }}"
        class="btn btn-primary">{{ __('messages.supplier.new_supplier') }}</a>
        </a>
        @endmodulePermission
</div>
