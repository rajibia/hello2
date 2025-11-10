<div class="row">
    {{ Form::hidden('currency_symbol', getCurrentCurrency(), ['class' => 'currencySymbol']) }}
    
    <div class="col-md-4">
        <div class="form-group mb-5">
        {{ Form::label('insurance_id', 'Insurance: ', ['class' => 'form-label']) }}
        <span class="required"></span>
        {{ Form::select('insurance_id', $insurances,null, ['id'=>'insurance_id','class' => 'form-select','required','data-control' => 'select2']) }}
        </div>
    </div>
    <div class="col-md-4">
        <div class="form-group mb-5">
            {{ Form::label('name', __('messages.package.service').' / Speciality Name:', ['class' => 'form-label']) }}
            <span class="required"></span>
            {{ Form::text('name', null, ['class' => 'form-control ','required','placeholder'=>__('messages.package.service')]) }}
        </div>
    </div>
    
    <div class="col-md-4">
        <div class="form-group mb-5">
            {{ Form::label('charge_status', 'Charge status:', ['class' => 'form-label']) }}
            <span class="required"></span>
            {{ Form::select('charge_status', ['Auto'=> 'Auto', 'Main Services' => 'Main Services', 'Manual / Others' => 'Manual / Others'], null, ['class' => 'form-select', 'id' => 'charge_status', 'required', 'data-control' => 'select2', 'tabindex' => "9"]) }}
        </div>
    </div>
    <div class="col-md-4">
        <div class="form-group mb-5">
            {{ Form::label('age', 'Age:', ['class' => 'form-label']) }}
            <span class="required"></span>
            {{ Form::select('age', ['Adult' => 'Adult', 'Child' => 'Child', 'Both' => 'Both'], null, ['class' => 'form-select', 'id' => 'age', 'required', 'data-control' => 'select2', 'tabindex' => "9"]) }}
        </div>
    </div>

    <div class="col-md-4">
        <div class="form-group mb-5">
            {{ Form::label('icd_code', ' G - DRG Code:', ['class' => 'form-label']) }}
            <span class="required"></span>
            {{ Form::text('icd_code', null, ['class' => 'form-control ','required','placeholder'=>'G - DRG Code']) }}
        </div>
    </div>

    <div class="col-md-4">
        <div class="form-group mb-5">
            {{ Form::label('rate', __('messages.service.rate').' / Insurance Tariff:', ['class' => 'form-label']) }}
            <span class="required"></span>
            {{ Form::text('rate', null, ['class' => 'form-control price-input ', 'onkeyup' => 'this.value = this.value.replace(/[^\\d.]/g, \'\').replace(/\\./, \'$#$\').replace(/\\./g, \'\').replace(/\\$#\\$/, \'.\')', 'maxlength' => '7','required','placeholder'=>__('messages.package.rate')]) }}
        </div>
    </div>

    <div class="col-md-4">
        <div class="form-group mb-5">
            {{ Form::label('topup', 'Top up:', ['class' => 'form-label']) }}
            {{-- <span class="required"></span> --}}
            {{ Form::text('topup', null, ['class' => 'form-control price-input ', 'onkeyup' => 'this.value = this.value.replace(/[^\\d.]/g, \'\').replace(/\\./, \'$#$\').replace(/\\./g, \'\').replace(/\\$#\\$/, \'.\')', 'maxlength' => '7','placeholder'=>'Top-up amount']) }}
        </div>
    </div>

    <div class="col-md-4">
        <div class="form-group mb-5">
            {{ Form::label('non_insured_amount', 'Non_insured Amount:', ['class' => 'form-label']) }}
            {{-- <span class="required"></span> --}}
            {{ Form::text('non_insured_amount', null, ['class' => 'form-control price-input ', 'onkeyup' => 'this.value = this.value.replace(/[^\\d.]/g, \'\').replace(/\\./, \'$#$\').replace(/\\./g, \'\').replace(/\\$#\\$/, \'.\')', 'maxlength' => '7','placeholder'=>'Non-insured amount']) }}
        </div>
    </div>

  
    {{-- <div class="col-md-4">
        <div class="form-group mb-5">
            {{ Form::label('quantity', __('messages.service.quantity').':', ['class' => 'form-label']) }}
            <span class="required"></span>
            {{ Form::text('quantity', null, ['class' => 'form-control ', 'onkeyup' => 'this.value = this.value.replace(/[^\\d.]/g, \'\').replace(/\\./, \'$#$\').replace(/\\./g, \'\').replace(/\\$#\\$/, \'.\')', 'maxlength' => '5','required','placeholder'=>__('messages.service.quantity')]) }}
        </div>
    </div> --}}
    
    <div class="col-md-4">
        <div class="form-group mb-5">
            {{ Form::label('speciality_code', ' Speciality Code:', ['class' => 'form-label']) }}
            <span class="required"></span>
            {{ Form::text('speciality_code', null, ['class' => 'form-control ','required','placeholder'=>'Speciality Code']) }}
        </div>
    </div>

    <div class="col-md-6">
        <div class="form-group mb-5">
            {{ Form::label('description', 'Speciality '.__('messages.common.description').':', ['class' => 'form-label']) }}
            {{ Form::text('description', null, ['class' => 'form-control ','placeholder'=>'Speciality '.__('messages.package.description')]) }}
        </div>
    </div>
    <div class="form-group col-sm-6 mb-5">
        {{ Form::label('status', __('messages.common.status').(':'), ['class' => 'form-label']) }}
        <div class="form-check form-switch">
            <input class="form-check-input w-35px h-20px is-active" name="status" type="checkbox"
                   value="1" {{(!isset($service)) ? 'checked':(($service->status) ? 'checked' : '')}}>
        </div>
    </div>
    <div class="d-flex justify-content-end">
        {{ Form::submit(__('messages.common.save'), ['class' => 'btn btn-primary me-2', 'id' => 'serviceBtnSave']) }}
        <a href="{{ route('services.index') }}"
           class="btn btn-secondary me-2">{{ __('messages.common.cancel') }}</a>
    </div>
</div>
