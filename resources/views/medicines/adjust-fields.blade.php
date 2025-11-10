{{ Form::hidden('user_id', auth()->user()->id, ['class' => 'currencySymbol']) }}

<div class="form-group col-md-12 mb-5">
    {{ Form::label('available_quantity', __('messages.issued_item.available_quantity') . ':', ['class' => 'form-label']) }}
    {{ Form::number('available_quantity', isset($medicine) ? $medicine->available_quantity : 0, ['class' => 'form-control', 'placeholder' => __('messages.issued_item.available_quantity'), 'id' => 'availableQuantityId']) }}
</div>

<div class="form-group col-md-12 mb-5">
    {{ Form::label('store_quantity', 'Store Quantity' . ':', ['class' => 'form-label']) }}
    {{ Form::number('store_quantity', isset($medicine) ? $medicine->store_quantity : 0, ['class' => 'form-control', 'placeholder' => 'Store Quantity', 'id' => 'storeQuantityId']) }}
</div>


<br>
<!-- Submit Field -->
<div class="d-flex justify-content-end">
    {{ Form::submit(__('messages.common.save'), ['class' => 'btn btn-primary me-2', 'id' => 'medicineSave']) }}
    <a href="{{ route('medicines.index') }}" class="btn btn-secondary">{{ __('messages.common.cancel') }}</a>
</div>
