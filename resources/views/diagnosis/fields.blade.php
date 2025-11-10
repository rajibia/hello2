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
                    {{ Form::label('age', 'Age:', ['class' => 'form-label']) }}
                    <span class="required"></span>
                    {{ Form::select('age', ['Adult' => 'Adult', 'Child' => 'Child', 'Both' => 'Both'], null, ['class' => 'form-select', 'id' => 'age', 'required', 'data-control' => 'select2', 'tabindex' => "9"]) }}
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group mb-5">
                    {{ Form::label('name', __('messages.package.diagnosis').' Name:', ['class' => 'form-label']) }}
                    <span class="required"></span>
                    {{ Form::text('name', null, ['class' => 'form-control ','required','placeholder'=>__('messages.package.diagnosis')]) }}
                </div>
            </div>
        
            <div class="col-md-4">
                <div class="form-group mb-5">
                    {{ Form::label('gdrg_code', ' G - DRG Code:', ['class' => 'form-label']) }}
                    <span class="required"></span>
                    {{ Form::text('gdrg_code', null, ['class' => 'form-control ','required','placeholder'=>'G - DRG Code']) }}
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group mb-5">
                    {{ Form::label('icd_10_code', ' ICD 10 Code:', ['class' => 'form-label']) }}
                    <span class="required"></span>
                    {{ Form::text('icd_10_code', null, ['class' => 'form-control ','required','placeholder'=>'ICD 10 Code']) }}
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group mb-5">
                    {{ Form::label('tariff', __('messages.diagnosis.rate').' / Insurance Tariff:', ['class' => 'form-label']) }}
                    <span class="required"></span>
                    {{ Form::text('tariff', null, ['class' => 'form-control price-input ', 'onkeyup' => 'this.value = this.value.replace(/[^\\d.]/g, \'\').replace(/\\./, \'$#$\').replace(/\\./g, \'\').replace(/\\$#\\$/, \'.\')', 'maxlength' => '7','required','placeholder'=>__('messages.package.rate')]) }}
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
        
          
            <div class="col-md-4">
                <div class="form-group mb-5">
                    {{ Form::label('grouping', 'Grouping:', ['class' => 'form-label']) }}
                    <span class="required"></span>
                    {{ Form::text('grouping', null, ['class' => 'form-control ','required','placeholder'=>'Grouping']) }}
                </div>
            </div>
            
            <div class="col-md-4">
                <div class="form-group mb-5">
                    {{ Form::label('speciality_code', 'Speciality Code:', ['class' => 'form-label']) }}
                    <span class="required"></span>
                    {{ Form::text('speciality_code', null, ['class' => 'form-control ','required','placeholder'=>'Speciality Code']) }}
                </div>
            </div>
            
            
            <div class="col-md-4">
                <div class="form-group mb-5">
                    {{ Form::label('speciality_description', 'Speciality '.__('messages.common.description').':', ['class' => 'form-label']) }}
                    {{ Form::text('speciality_description', null, ['class' => 'form-control ','placeholder'=>'Speciality '.__('messages.package.description')]) }}
                </div>
            </div>
            <div class="form-group col-sm-2 mb-"">
                {{ Form::label('status', __('messages.common.status').(':'), ['class' => 'form-label']) }}
                <div class="form-check form-switch">
                    <input class="form-check-input w-35px h-20px is-active" name="status" type="checkbox"
                           value="1" {{(!isset($diagnosis)) ? 'checked':(($diagnosis->status) ? 'checked' : '')}}>
                </div>
            </div>
            
            <div class="d-flex justify-content-end">
                {{ Form::submit(__('messages.common.save'), ['class' => 'btn btn-primary me-2', 'id' => 'diagnosisBtnSave']) }}
                <a href="{{ route('diagnosis.index') }}"
                   class="btn btn-secondary me-2">{{ __('messages.common.cancel') }}</a>
            </div>
        </div>
        
        