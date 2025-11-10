<div class="row">
    <div class="col-md-2">
        <div class="form-group mb-5">
        {{ Form::label('insurance', 'Insurance: ', ['class' => 'form-label']) }}
        <span class="required"></span>
        {{ Form::select('insurance', $insurances,null, ['id'=>'insurance','class' => 'form-select','required','data-control' => 'select2']) }}
        </div>
    </div>
    <div class="col-md-2">
        <div class="form-group mb-5">
            {{ Form::label('name', __('messages.package.lab').' Name:', ['class' => 'form-label']) }}
            <span class="required"></span>
            {{ Form::text('name', null, ['class' => 'form-control ','required','placeholder'=>__('messages.package.lab')]) }}
        </div>
    </div>

    <div class="col-md-2">
        <div class="form-group mb-5">
            {{ Form::label('gdrg_code', ' G - DRG Code:', ['class' => 'form-label']) }}
            <span class="required"></span>
            {{ Form::text('gdrg_code', null, ['class' => 'form-control ','required','placeholder'=>'G - DRG Code']) }}
        </div>
    </div>

    <div class="col-md-2">
        <div class="form-group mb-5">
            {{ Form::label('tariff', __('messages.lab.rate').' / Insurance Tariff:', ['class' => 'form-label']) }}
            <span class="required"></span>
            {{ Form::text('tariff', null, ['class' => 'form-control price-input ', 'onkeyup' => 'if (/\D/g.test(this.value)) this.value = this.value.replace(/\D/g,"")', 'maxlength' => '7','required','placeholder'=>__('messages.package.rate')]) }}
        </div>
    </div>

    <div class="col-md-2">
        <div class="form-group mb-5">
            {{ Form::label('topup', 'Top up:', ['class' => 'form-label']) }}
            {{-- <span class="required"></span> --}}
            {{ Form::text('topup', null, ['class' => 'form-control price-input ', 'onkeyup' => 'if (/\D/g.test(this.value)) this.value = this.value.replace(/\D/g,"")', 'maxlength' => '7','placeholder'=>'Top-up amount']) }}
        </div>
    </div>

    <div class="col-md-2">
        <div class="form-group mb-5">
            {{ Form::label('non_insured_amount', 'Non_insured Amount:', ['class' => 'form-label']) }}
            {{-- <span class="required"></span> --}}
            {{ Form::text('non_insured_amount', null, ['class' => 'form-control price-input ', 'onkeyup' => 'if (/\D/g.test(this.value)) this.value = this.value.replace(/\D/g,"")', 'maxlength' => '7','placeholder'=>'Non-insured amount']) }}
        </div>
    </div>

    <div class="form-group col-sm-6">
        {{ Form::label('status', __('messages.common.status').(':')) }}
        <label class="switch switch-label switch-outline-primary-alt d-block">
            <input name="status" class="switch-input" type="checkbox" value="1"
                    {{(!isset($insurance)) ? 'checked':(($insurance->status) ? 'checked' : '')}}>
            <span class="switch-slider" data-checked="&#x2713;" data-unchecked="&#x2715;"></span>
        </label>
    </div>
    
    <div class="form-group col-sm-12">
        {{ Form::submit(__('messages.common.save'), ['class' => 'btn btn-primary', 'id' => 'editLabsBtnSave']) }}
        <a href="{{ route('labs.index') }}" class="btn btn-secondary">{{ __('messages.common.cancel') }}</a>
    </div>
</div>
