<div class="alert alert-danger d-none" id="supplierErrorBox"></div>
<div class="row gx-10 mb-5">
    <div class="col-md-6">
        <div class="form-group mb-5">
            {{ Form::label('name', __('messages.user.name').':', ['class' => 'form-label']) }}
            <span class="required"></span>
            {{ Form::text('name', null, ['class' => 'form-control', 'required', 'id' => 'supplierFirstName','tabindex' => '1','placeholder'=>__('messages.user.first_name')]) }}
        </div>
    </div>
    
    <div class="col-md-6">
        <div class="form-group mb-5">
            {{ Form::label('email', __('messages.user.email').':', ['class' => 'form-label']) }}
            <span class="required"></span>
            {{ Form::text('email', null, ['class' => 'form-control','required','placeholder'=>__('messages.user.email')]) }}
        </div>
    </div>
    
    <div class="col-md-6">
        <div class="form-group mobile-overlapping  mb-5">
            {{ Form::label('phone', __('messages.user.phone').':', ['class' => 'form-label']) }}
            <span class="required"></span><br>
            {{ Form::tel('phone', getCountryCode(), ['class' => 'form-control phoneNumber', 'id' => 'supplierPhoneNumber', 'required', 'onkeyup' => 'if (/\D/g.test(this.value)) this.value = this.value.replace(/\D/g,"")', 'tabindex' => '5']) }}
            {{ Form::hidden('prefix_code',null,['class'=>'prefix_code']) }}
            <span class="text-success valid-msg d-none fw-400 fs-small mt-2">✓ &nbsp; {{__('messages.valid')}}</span>
            <span class="text-danger error-msg d-none fw-400 fs-small mt-2"></span>
        </div>
    </div>
    
    <div class="col-md-6">
        <div class="form-group mb-5">
            {{ Form::label('status', __('messages.common.status').':', ['class' => 'form-label']) }}
            <div class="form-check form-switch form-check-custom">
                <input class="form-check-input w-35px h-20px is-active" name="status" type="checkbox" value="1"
                    tabindex="8" checked>
            </div>
        </div>
    </div>
    
</div>
<hr>
<div class="row mt-3 mb-5">
    <div class="col-md-12 mb-3">
        <h5>{{ __('messages.user.address_details') }}</h5>
    </div>
    <div class="col-md-6">
        <div class="form-group mb-5">
            {{ Form::label('address1', __('messages.user.address1').':', ['class' => 'form-label']) }}
            {{ Form::text('address', null, ['class' => 'form-control','placeholder'=>__('messages.user.address1')]) }}
        </div>
    </div>
   
    <div class="col-md-6">
        <div class="form-group mb-5">
            {{ Form::label('city', __('messages.user.city').':', ['class' => 'form-label']) }}
            {{ Form::text('city', null, ['class' => 'form-control','placeholder'=>__('messages.user.city')]) }}
        </div>
    </div>
   
</div>

<div class="d-flex justify-content-end">
    {{ Form::submit(__('messages.common.save'), ['class' => 'btn btn-primary me-2','id' => 'supplierSave']) }}
    <a href="{{ route('suppliers.index') }}"
       class="btn btn-secondary me-2">{!! __('messages.common.cancel') !!}</a>
</div>
