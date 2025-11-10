{{-- <div class="card-toolbar ms-auto">
    <div class="dropdown">
        <a href="#" class="btn btn-primary dropdown-toggle" id="dropdownMenuButton"
           data-bs-toggle="dropdown"
           aria-haspopup="true" aria-expanded="false">{{ __('messages.common.actions') }}
        </a>
        <ul class="dropdown-menu action-dropdown" aria-labelledby="dropdownMenuButton">
            @modulePermission('medicine-purchase', 'add')
            <li>
                <a href="{{ route('medicine-purchase.create') }}"
                   class="dropdown-item  px-5">{{ __('messages.purchase_medicine.purchase_medicine') }}</a>
            </li>
            @endmodulePermission
            @modulePermission('medicine-purchase', 'view')
            <li>
                <a
                        href="{{ route('purchase-medicine.excel') }}"
                   class="dropdown-item  px-5" data-turbo="false">{{ __('messages.common.export_to_excel') }}</a>
            </li>
            @endmodulePermission
        </ul>
    </div>
</div> --}}


<div>
	@modulePermission('medicine-purchase', 'view')
    <a href="{{ route('purchase-medicine.excel') }}"
       class="btn btn-primary me-4"    data-turbo='false'>
       <i class="fas fa-file-excel"></i>
    </a>
    @endmodulePermission
    @modulePermission('medicine-purchase', 'add')
    <a href="{{ route('medicine-purchase.create') }}"
    class="btn btn-primary">{{ __('messages.purchase_medicine.purchase_medicine') }}</a>
    </a>
    @endmodulePermission
</div>
