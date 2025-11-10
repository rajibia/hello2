{{ Form::hidden('user_id', auth()->user()->id, ['class' => 'currencySymbol']) }}

<!-- Transfer From Field -->
<div class="form-group col-md-6 mb-5">
    {{ Form::label('transfer_from', __('messages.transfer_from') . ':', ['class' => 'form-label']) }}
    <span class="required"></span>
    {{ Form::select('transfer_from', ['' => 'Select One', 'Dispensary' => 'Dispensary (Quantity: '.($medicine->available_quantity) .')', 'Store' => 'Store (Quantity: '.($medicine->store_quantity) . ')'], null, ['class' => 'form-select', 'placeholder' => __('messages.select_transfer_from'), 'id' => 'medicineTransferFrom']) }}
</div>

<!-- Transfer To Field -->
<div class="form-group col-md-6 mb-5">
    {{ Form::label('transfer_to', __('messages.transfer_to') . ':', ['class' => 'form-label']) }}
    <span class="required"></span>
    {{ Form::select('transfer_to', ['' => 'Select One', 'Dispensary' => 'Dispensary', 'Store' => 'Store'], null, ['class' => 'form-select', 'placeholder' => __('messages.select_transfer_to'), 'id' => 'medicineTransferTo']) }}
</div>

<!-- Quantity Field -->

<div class="form-group col-md-12 mb-5">
    {{ Form::hidden('store_quantity', $medicine->store_quantity,  ['class' => 'form-control', 'placeholder' => __('messages.store_quantity')]) }}
    {{ Form::hidden('available_quantity', $medicine->available_quantity,  ['class' => 'form-control', 'placeholder' => __('messages.available_quantity')]) }}
    {{ Form::hidden('quantity', $medicine->quantity,  ['class' => 'form-control', 'placeholder' => __('messages.quantity')]) }}
    <!-- Available Quantity Field -->
    {{ Form::label('transfer_quantity', __('messages.transfer_quantity') . ':', ['class' => 'form-label']) }}
    {{ Form::number('transfer_quantity',0, ['class' => 'form-control', 'placeholder' => __('messages.transfer_quantity'), 'id' => 'transferQuantityId']) }}
</div>


<br>
<!-- Submit Field -->
<div class="d-flex justify-content-end">
    {{ Form::submit(__('messages.common.save'), ['class' => 'btn btn-primary me-2', 'id' => 'medicineSave']) }}
    <a href="{{ route('medicines.index') }}" class="btn btn-secondary">{{ __('messages.common.cancel') }}</a>
</div>
