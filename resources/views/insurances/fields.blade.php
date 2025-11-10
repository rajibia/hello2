<div class="row">
    <div class="form-group col-sm-6 mb-5">
        {{ Form::label('name', 'Insurance name:', ['class' => 'form-label']) }}
        <span class="required"></span>
        {{ Form::text('name', null, ['class' => 'form-control ', 'required','placeholder'=>'Insurance name']) }}
    </div>
    <div class="form-group col-sm-6 mb-5">
        {{ Form::label('insurance_code', 'Insurance Code:', ['class' => 'form-label fs-6']) }}
        <span class="required"></span>
        {{ Form::text('insurance_code', null, ['class' => 'form-control  code', 'required','placeholder'=>'Insurance Code']) }}
    </div>
    <div class="form-group col-sm-6 mb-5">
        {{ Form::label('other_identification', 'Other Identification: ', ['class' => 'form-label']) }}
        {{ Form::text('other_identification', null, [ 'class' => 'form-control other_identification', 'placeholder' => 'Other Identification']) }}
        {{-- 'id' => 'insuranceDiscountId', 'min' => 0, 'max' => 100, 'step' => '.01', --}}
    </div>
    <div class="form-group col-sm-6 mb-5">
        {{ Form::label('claim_check_code', 'Claim Check Code: ', ['class' => 'form-label']) }}
        <span class="required"></span>
        {{ Form::select('claim_check_code', ['Required','Optional'],null, ['id'=>'claim_check_code','class' => 'form-select','required','data-control' => 'select2']) }}
        
    </div>
    <div class="form-group col-sm-6 mb-5">
        {{ Form::label('non_insurance_medication', 'Non-Insurance Medication: ', ['class' => 'form-label']) }}
        <span class="required"></span>
        {{ Form::select('non_insurance_medication', ['Yes','No'],null, ['id'=>'non_insurance_medication','class' => 'form-select','required','data-control' => 'select2']) }}
    </div>
    <div class="form-group col-sm-6 mb-5">
        {{ Form::label('visit_per_month', 'Visit Per Month: ', ['class' => 'form-label']) }}
        {{ Form::number('visit_per_month', null, [ 'class' => 'form-control visit_per_month', 'min' => 0, 'placeholder' => 'Visit Per Month']) }}
    </div>
    <div class="form-group col-sm-6 mb-5">
        {{ Form::label('claim_code_count', 'Claim Check Code Count: ', ['class' => 'form-label']) }}
        <span class="required" id="claim_code_count_required"></span>
        {{ Form::number('claim_code_count', null, [ 'class' => 'form-control claim_code_count', 'min' => 0, 'required', 'placeholder' => 'Claim Check Code Count']) }}
    </div>
    <div class="form-group col-sm-6 mb-5">
        {{ Form::label('membership_no_count', 'Member Number Count: ', ['class' => 'form-label']) }}
        {{ Form::number('membership_no_count', null, [ 'class' => 'form-control membership_no_count', 'min' => 0, 'placeholder' => 'Member Number Count']) }}
    </div>
    <div class="form-group col-sm-6 mb-5">
        {{ Form::label('card_type', 'Card Type:', ['class' => 'form-label fs-6']) }}
        <span class="required"></span>
        {{ Form::text('card_type', null, ['class' => 'form-control card_type', 'required','placeholder'=>'Card Type']) }}
    </div>
    <div class="form-group col-sm-6 mb-5">
        {{ Form::label('card_serial_no_count', 'Card Serial Number Count: ', ['class' => 'form-label']) }}
        {{ Form::number('card_serial_no_count', null, [ 'class' => 'form-control card_serial_no_count', 'min' => 0, 'placeholder' => 'Card Serial Number Count']) }}
    </div>

    

    <div class="form-group col-md-4 mb-5">
        <div class="row2" io-image-input="true">
            {{ Form::label('image', 'Logo:', ['class' => 'form-label']) }}


            <div class="d-block">
                @php
                    if (!isset($isEdit)) {
                        $isEdit=0;
                    }
                    if ($isEdit) {
                        $image = isset($insurance->media[0]) ? $insurance->image : asset('assets/img/avatar.png');
                    } else {
                        $image = asset('assets/img/avatar.png');
                    }
                @endphp

                <div class="image-picker">
                    <div class="image previewImage" id="userPreviewImage"
                        style="background-image: url({{ $image }})">
                        <span class="picker-edit rounded-circle text-gray-500 fs-small"
                            title="{{ $isEdit ? 'Change logo' : 'Logo'}}">

                            <label>
                                <i class="fa-solid fa-pen" id="profileImageIcon"></i>
                                <input type="file" id="userProfileImage" name="image"
                                    class="image-upload d-none profileImage" ,
                                    accept=".png, .jpg, .jpeg, .gif" />
                                <input type="hidden" name="avatar_remove" />
                            </label>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <div class="form-group col-sm-6 mb-5">
        {{ Form::label('status', __('messages.common.status') . ':', ['class' => 'form-label fs-6 fw-']) }}
        <div class="form-check form-switch">
            <input class="form-check-input w-35px h-20px is-active" name="status" type="checkbox" value="1"
                tabindex="8" {{ !isset($insurance) ? 'checked' : ($insurance->status ? 'checked' : '') }}>
        </div>
    </div>

    {{--
    <div class="form-group col-md-6 mb-5">
        <div class="row2" io-image-input="true">
            {{ Form::label('image',('Insurance logo:'), ['class' => 'form-label']) }}
            <div class="d-block">
                <?php
                $style = 'style=';
                $background = 'background-image:';
                ?>

                <div class="image-picker">
                    <div class="image previewImage" id="patientPreviewImage"
                        {{$style}}"{{$background}} url({{ asset('assets/img/avatar.png')}}">
                        <span class="picker-edit rounded-circle text-gray-500 fs-small" title="Logo">
                            <label>
                                <i class="fa-solid fa-pen" id="profileImageIcon"></i>
                                <input type="file" id="patientProfileImage" name="image"
                                    class="image-upload d-none profileImage patientProfileImage" accept=".png, .jpg, .jpeg, .gif"/>
                                <input type="hidden" name="avatar_remove"/>
                            </label>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    --}}

    <div class="col-sm-12 mt-3">
        <div class="row">
            <div class="col-lg-8 mb-3 h5">
                Insurance Packages
            </div>
            <div class="col-lg-4">
                <div class="col-lg-12 col-md-12 col-sm-12 d-flex justify-content-end">
                    <button type="button" class="btn btn-primary text-star"
                        id="addInsuranceItem">{{ __('messages.common.add') }}</button>
                </div>
            </div>
        </div>
        <div class="table-responsive-sm">
            <table class="table table-striped" id="insuranceBillTbl">
                <thead>
                    <tr class="text-start text-muted fw-bolder fs-7 text-uppercase gs-0">
                        <th class="text-center">#</th>
                        <th class="insurance-name form-label fs-6 fw-bolder text-gray-700 mb-3'">
                            Package Name
                            <span class="required"></span>
                        </th>
                        <th class="table__add-btn-heading text-center form-label fs-6 fw-bolder text-gray-700 mb-3">
                            {{ __('messages.common.action') }}
                        </th>
                    </tr>
                </thead>
                <tbody class="disease-item-container">
                    @if (isset($packages))
                        @foreach ($packages as $package)
                            <tr>
                                <td class="text-center item-number">{{ $loop->iteration }}</td>
                                <td>
                                    {{ Form::text('package_name[]', $package->package_name, ['class' => 'form-control package-name ', 'required','placeholder' => 'Package name']) }}
                                </td>
                                <td class="text-center">
                                    <a href="javascript:void(0)" title="{{ __('messages.common.delete') }}"
                                        class="delete-disease btn px-1 text-danger fs-3 pe-0">
                                        <i class="fa-solid fa-trash"></i>
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    @else
                        <tr>
                            <td class="text-center item-number">1</td>
                            <td>
                                {{ Form::text('package_name[]', null, ['class' => 'form-control  package-name', 'required','placeholder' => 'Package name']) }}
                            </td>
                            <td class="text-center">
                                <a href="javascript:void(0)" title="{{ __('messages.common.delete') }}"
                                    class="delete-disease btn px-1 text-danger fs-3 pe-0">
                                    <i class="fa-solid fa-trash"></i>
                                </a>
                            </td>
                        </tr>
                    @endif
                </tbody>
            </table>
            <div class="float-end p-0 mb-3">
                <table>
                    <tbody>
                        <tr>
                            <td class="font-weight-bold form-label fs-6 fw-bolder text-gray-700 mb-3">
                            <td class="text-right">
                                <span id="insuranceTotal" class="totalAmount ms-2">
                                </span>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
   
    {{ Form::hidden('total', null, ['class' => 'form-control', 'id' => 'insuranceTotal_amount']) }}
    <div class="d-flex justify-content-end">
        {{ Form::submit(__('messages.common.save'), ['class' => 'btn btn-primary me-2', 'id' => 'insuranceSaveBtn']) }}
        <a href="{{ route('insurances.index') }}"
            class="btn btn-secondary me-2">{{ __('messages.common.cancel') }}</a>
    </div>
</div>
