<div class="alert alert-danger d-none hide" id="editPatientErrorsBox"></div>
<div class="row">
    <div class="col-md-12 mb-3">
        <h4>{{ __('messages.user.personal_information') }}</h4>
    </div>
    <div class="col-md-3">
        <div class="form-group mb-5">
            {{ Form::label('first_name', __('messages.user.first_name').':', ['class' => 'form-label']) }}
            <span class="required"></span>
            {{ Form::text('first_name', null, ['class' => 'form-control', 'required', 'tabindex' => '1','placeholder'=>__('messages.user.first_name')]) }}
        </div>
    </div>
    <div class="col-md-3">
        <div class="form-group mb-5">
            {{ Form::label('last_name', __('messages.user.last_name').':', ['class' => 'form-label']) }}
            <span class="required"></span>
            {{ Form::text('last_name', null, ['class' => 'form-control', 'required', 'tabindex' => '2','placeholder'=>__('messages.user.last_name')]) }}
        </div>
    </div>
    <div class="col-md-3">
        <div class="form-group mb-5 d-none">
            {{ Form::label('dob', __('messages.user.dob').':', ['class' => 'form-label']) }}
            {{ Form::text('dob', null, ['class' => (getLoggedInUser()->thememode ? 'bg-light patientBirthDate form-control' : 'bg-white patientBirthDate form-control'), 'id' => 'editPatientBirthDate', 'autocomplete' => 'off', 'tabindex' => '4','placeholder'=>__('messages.user.dob')]) }}
        </div>
        <div class="form-group mb-5">
            {{ Form::label('age', __('messages.age').':', ['class' => 'form-label']) }}
            {{ Form::text('age_new', null, ['class' => (getLoggedInUser()->thememode ? 'bg-light form-control' : 'bg-white form-control'), 'id' => 'editPatientAge', 'autocomplete' => 'off', 'tabindex' => '4','placeholder'=>__('messages.age')]) }}
        </div>
    </div>
    <div class="col-md-3">
        <div class="form-group mobile-overlapping  mb-5">
            {{ Form::label('phone', __('messages.user.phone').':', ['class' => 'form-label']) }}
            <span class="required"></span><br>
            {{ Form::tel('phone', $patient->patientUser->phone ?? getCountryCode(), ['class' => 'form-control phoneNumber', 'id' => 'editPatientPhoneNumber', 'required', 'onkeyup' => 'if (/\D/g.test(this.value)) this.value = this.value.replace(/\D/g,"")', 'tabindex' => '5']) }}
            {{ Form::hidden('prefix_code',null,['class'=>'prefix_code']) }}
            {{ Form::hidden('country_iso',null,['class'=>'country_iso']) }}
            <span class="text-success valid-msg d-none fw-400 fs-small mt-2">✓ &nbsp; {{__('messages.valid')}}</span>
            <span class="text-danger error-msg d-none fw-400 fs-small mt-2"></span>
        </div>
    </div>
    <div class="col-md-3">
        <div class="form-group mb-5">
            {{ Form::label('gender', __('messages.user.gender').':', ['class' => 'form-label']) }}
            <span
                    class="required"></span> &nbsp;<br>
            <span class="is-valid">
                <label class="form-label">{{ __('messages.user.male') }}</label>&nbsp;&nbsp;
                {{ Form::radio('gender', '0', true, ['class' => 'form-check-input', 'tabindex' => '6','id'=>'editPatientMale']) }} &nbsp;
                <label class="form-label">{{ __('messages.user.female') }}</label>
                {{ Form::radio('gender', '1', false, ['class' => 'form-check-input', 'tabindex' => '7','id'=>'editPatientFemale']) }}
            </span>
        </div>
    </div>
    <div class="col-md-3">
        <div class="form-group mb-5">
            {{ Form::label('blood_group', __('messages.user.blood_group').':', ['class' => 'form-label']) }}
            {{ Form::select('blood_group', $bloodGroup, null, ['class' => 'form-select', 'id' => 'editPatientBloodGroup', 'placeholder' => __('messages.user.select_blood_group'), 'data-control' => 'select2', 'tabindex' => "9"]) }}
        </div>
    </div>
    <div class="col-md-3">
        <div class="form-group mb-5">
            {{ Form::label('insurance_number', __('messages.insurance_number'), ['class' => 'form-label']) }}
            {{ Form::text('insurance_number', $patient->insurance_number, ['class' => 'form-control','placeholder'=>__('messages.insurance_number')]) }}
        </div>
    </div>
   @php
    $selectedType = !is_null($patient->company_id) ? 'company' : 'individual';
@endphp

<div class="col-md-3">
    <div class="form-group mb-5">
        {{ Form::label('patient_type', 'Type', ['class' => 'form-label']) }}
        <span class="required"></span>
        {{ Form::select('patient_type', ['company' => 'Company', 'individual' => 'Individual'], $selectedType, [
            'class' => 'form-select',
            'id' => 'patient_type',
            'placeholder' => 'Select Type'
        ]) }}
    </div>
</div>
{{--
    <div class="col-md-3">
        <div class="form-group mb-5">
            {{ Form::label('patient_type', 'Type', ['class' => 'form-label']) }}
            <span class="required"></span>
            {{ Form::select('patient_type', ['company' => 'Company', 'individual' => 'Individual'], null, [
                'class' => 'form-select',
                'id' => 'patient_type',
                'placeholder' => 'Select Type'
            ]) }}
        </div>
    </div>
--}}
    <div class="row col-md-6" id="company_info" @if(is_null($patient->company_id)) style="display:none" @endif>
        <div class="col-md-3">
            <div class="form-group mb-5">
                {{ Form::label('company_id', 'Company', ['class' => 'form-label']) }}
                {{ Form::select('company_id', [], null, [
                    'class' => 'form-select',
                    'id' => 'company_id',
                    'placeholder' => 'Select Company'
                ]) }}
            </div>
        </div>
        <div class="col-md-3">
            <div class="form-group mb-5">
                {{ Form::label('staff_id', 'Staff Id', ['class' => 'form-label']) }}
                {{ Form::text('staff_id', $patient->staff_id, [
                    'class' => 'form-control',
                    'id' => 'staff_id',
                    'placeholder' => 'Staff Id'
                ]) }}
            </div>
        </div>
    </div>
    <hr>
    <div class="row mt-3 mb-5">
        <div class="col-md-12 mb-3">
            <h4>{{ __('messages.user.emergency_contact') }}</h4>
        </div>
        <div class="col-md-4">
            <div class="form-group mb-5">
                {{ Form::label('guardian_name', __('messages.user.name').':', ['class' => 'form-label']) }}
                
                {{ Form::text('guardian_name', $patient->guardian_name, ['class' => 'form-control', 'id' => 'guardianName','tabindex' => '1','placeholder'=>__('messages.user.guardian_name')]) }}
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group mobile-overlapping  mb-5">
                {{ Form::label('guardian_phone', __('messages.user.phone').':', ['class' => 'form-label']) }}
                
                {{ Form::tel('guardian_phone', getCountryCode(), ['class' => 'form-control phoneNumber', 'id' => 'guardianPhoneNumber', 'onkeyup' => 'if (/\D/g.test(this.value)) this.value = this.value.replace(/\D/g,"")', 'tabindex' => '5']) }}
                {{ Form::hidden('prefix_code',$patient->guardian_phone,['class'=>'prefix_code']) }}
                <span class="text-success valid-msg d-none fw-400 fs-small mt-2">✓ &nbsp; {{__('messages.valid')}}</span>
                <span class="text-danger error-msg d-none fw-400 fs-small mt-2"></span>
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group mb-5">
                {{ Form::label('guardian_relation', __('messages.user.relation').':', ['class' => 'form-label']) }}
                
                {{ Form::text('guardian_relation', $patient->guardian_relation, ['class' => 'form-control', 'id' => 'guardianRelation','tabindex' => '1','placeholder'=>__('messages.user.guardian_relation_placeholder')]) }}
            </div>
        </div>
    </div>
    <hr>
    <div class="row mt-3 mb-5">
        <div class="col-md-12 mb-3">
            <h5>{{ __('messages.user.address_details') }}</h5>
        </div>
        <div class="col-md-4">
            <div class="form-group mb-5">
                {{ Form::label('address1', __('messages.user.address1').':', ['class' => 'form-label']) }}
                {{ Form::text('address1', $patient->address->address1 ?? null, ['class' => 'form-control','placeholder'=>__('messages.user.address1')]) }}
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group mb-5">
                {{ Form::label('address2', __('messages.user.address2').':', ['class' => 'form-label']) }}
                {{ Form::text('address2', $patient->address->address2 ?? null, ['class' => 'form-control','placeholder'=>__('messages.user.address2')]) }}
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group mb-5">
                {{ Form::label('city', __('messages.user.city').':', ['class' => 'form-label']) }}
                {{ Form::text('city', $patient->address->city ?? null, ['class' => 'form-control','placeholder'=>__('messages.user.city')]) }}
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group mb-5">
                {{ Form::label('zip', __('messages.user.zip').':', ['class' => 'form-label']) }}
                {{ Form::text('zip', $patient->address->zip ?? null, ['class' => 'form-control', 'maxlength' => '6','placeholder'=>__('messages.user.zip')]) }}
                <!--,'minlength' => '6'-->
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group mb-5">
                {{ Form::label('location', __('messages.location').':', ['class' => 'form-label']) }}
                <!--<span class="required"></span>-->
                {{ Form::text('location', $patient->location, ['class' => 'form-control', 'tabindex' => '3','id'=>'editPatientLocation','placeholder'=>__('messages.location')]) }}
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group mb-5">
                {{ Form::label('email', __('messages.user.email').':', ['class' => 'form-label']) }}
                <!--<span class="required"></span>-->
                {{ Form::text('email', null, ['class' => 'form-control', 'required', 'tabindex' => '3','id'=>'editPatientEmail','placeholder'=>__('messages.user.email')]) }}
            </div>
        </div>
    </div>
    <hr>
    <div class="row mt-3 mb-5">
        <div class="col-md-12 mb-3">
            <h4>{{ __('messages.user.other_details') }}</h4>
        </div>
        <div class="col-md-3">
            <div class="form-group mb-5">
                {{ Form::label('occupation', __('messages.case.occupation') . ':', ['class' => 'form-label']) }}
                <!--<span class="required"></span>-->
                {{ Form::text('occupation', $patient->occupation, ['placeholder' => __('messages.case.occupation'),'tabindex' => '20' ,'id' => 'occupation', 'auto', 'class' => getLoggedInUser()->thememode ? 'bg-light form-control' : 'bg-white form-control', 'autocomplete' => 'off']) }}
            </div>
        </div>
        <div class="col-md-3">
            <div class="form-group mb-5">
                {{ Form::label('nationality', __('messages.case.nationality') . ':', ['class' => 'form-label']) }}
                {{ Form::select(
                    'nationality',
                    [
                        'Afghanistan' => 'Afghanistan',
                        'Albania' => 'Albania',
                        'Algeria' => 'Algeria',
                        'Andorra' => 'Andorra',
                        'Angola' => 'Angola',
                        'Argentina' => 'Argentina',
                        'Armenia' => 'Armenia',
                        'Australia' => 'Australia',
                        'Austria' => 'Austria',
                        'Azerbaijan' => 'Azerbaijan',
                        'Bahamas' => 'Bahamas',
                        'Bahrain' => 'Bahrain',
                        'Bangladesh' => 'Bangladesh',
                        'Barbados' => 'Barbados',
                        'Belarus' => 'Belarus',
                        'Belgium' => 'Belgium',
                        'Belize' => 'Belize',
                        'Benin' => 'Benin',
                        'Bhutan' => 'Bhutan',
                        'Bolivia' => 'Bolivia',
                        'Bosnia and Herzegovina' => 'Bosnia and Herzegovina',
                        'Botswana' => 'Botswana',
                        'Brazil' => 'Brazil',
                        'Brunei' => 'Brunei',
                        'Bulgaria' => 'Bulgaria',
                        'Burkina Faso' => 'Burkina Faso',
                        'Burundi' => 'Burundi',
                        'Cambodia' => 'Cambodia',
                        'Cameroon' => 'Cameroon',
                        'Canada' => 'Canada',
                        'Chile' => 'Chile',
                        'China' => 'China',
                        'Colombia' => 'Colombia',
                        'Costa Rica' => 'Costa Rica',
                        'Croatia' => 'Croatia',
                        'Cuba' => 'Cuba',
                        'Cyprus' => 'Cyprus',
                        'Czech Republic' => 'Czech Republic',
                        'Denmark' => 'Denmark',
                        'Dominican Republic' => 'Dominican Republic',
                        'Ecuador' => 'Ecuador',
                        'Egypt' => 'Egypt',
                        'El Salvador' => 'El Salvador',
                        'Estonia' => 'Estonia',
                        'Ethiopia' => 'Ethiopia',
                        'Fiji' => 'Fiji',
                        'Finland' => 'Finland',
                        'France' => 'France',
                        'Germany' => 'Germany',
                        'Ghana' => 'Ghana',
                        'Greece' => 'Greece',
                        'Guatemala' => 'Guatemala',
                        'Haiti' => 'Haiti',
                        'Honduras' => 'Honduras',
                        'Hungary' => 'Hungary',
                        'Iceland' => 'Iceland',
                        'India' => 'India',
                        'Indonesia' => 'Indonesia',
                        'Iran' => 'Iran',
                        'Iraq' => 'Iraq',
                        'Ireland' => 'Ireland',
                        'Israel' => 'Israel',
                        'Italy' => 'Italy',
                        'Jamaica' => 'Jamaica',
                        'Japan' => 'Japan',
                        'Jordan' => 'Jordan',
                        'Kazakhstan' => 'Kazakhstan',
                        'Kenya' => 'Kenya',
                        'Kuwait' => 'Kuwait',
                        'Laos' => 'Laos',
                        'Latvia' => 'Latvia',
                        'Lebanon' => 'Lebanon',
                        'Libya' => 'Libya',
                        'Lithuania' => 'Lithuania',
                        'Luxembourg' => 'Luxembourg',
                        'Madagascar' => 'Madagascar',
                        'Malawi' => 'Malawi',
                        'Malaysia' => 'Malaysia',
                        'Maldives' => 'Maldives',
                        'Mali' => 'Mali',
                        'Malta' => 'Malta',
                        'Mexico' => 'Mexico',
                        'Moldova' => 'Moldova',
                        'Monaco' => 'Monaco',
                        'Mongolia' => 'Mongolia',
                        'Montenegro' => 'Montenegro',
                        'Morocco' => 'Morocco',
                        'Mozambique' => 'Mozambique',
                        'Myanmar' => 'Myanmar',
                        'Namibia' => 'Namibia',
                        'Nepal' => 'Nepal',
                        'Netherlands' => 'Netherlands',
                        'New Zealand' => 'New Zealand',
                        'Nicaragua' => 'Nicaragua',
                        'Niger' => 'Niger',
                        'Nigeria' => 'Nigeria',
                        'North Korea' => 'North Korea',
                        'Norway' => 'Norway',
                        'Oman' => 'Oman',
                        'Pakistan' => 'Pakistan',
                        'Palestine' => 'Palestine',
                        'Panama' => 'Panama',
                        'Paraguay' => 'Paraguay',
                        'Peru' => 'Peru',
                        'Philippines' => 'Philippines',
                        'Poland' => 'Poland',
                        'Portugal' => 'Portugal',
                        'Qatar' => 'Qatar',
                        'Romania' => 'Romania',
                        'Russia' => 'Russia',
                        'Rwanda' => 'Rwanda',
                        'Saudi Arabia' => 'Saudi Arabia',
                        'Senegal' => 'Senegal',
                        'Serbia' => 'Serbia',
                        'Singapore' => 'Singapore',
                        'Slovakia' => 'Slovakia',
                        'Slovenia' => 'Slovenia',
                        'Somalia' => 'Somalia',
                        'South Africa' => 'South Africa',
                        'South Korea' => 'South Korea',
                        'Spain' => 'Spain',
                        'Sri Lanka' => 'Sri Lanka',
                        'Sudan' => 'Sudan',
                        'Sweden' => 'Sweden',
                        'Switzerland' => 'Switzerland',
                        'Syria' => 'Syria',
                        'Taiwan' => 'Taiwan',
                        'Tanzania' => 'Tanzania',
                        'Thailand' => 'Thailand',
                        'Tunisia' => 'Tunisia',
                        'Turkey' => 'Turkey',
                        'Uganda' => 'Uganda',
                        'Ukraine' => 'Ukraine',
                        'United Arab Emirates' => 'United Arab Emirates',
                        'United Kingdom' => 'United Kingdom',
                        'United States' => 'United States',
                        'Uruguay' => 'Uruguay',
                        'Uzbekistan' => 'Uzbekistan',
                        'Venezuela' => 'Venezuela',
                        'Vietnam' => 'Vietnam',
                        'Yemen' => 'Yemen',
                        'Zambia' => 'Zambia',
                        'Zimbabwe' => 'Zimbabwe',
                    ],
                    $patient->nationality,
                    [
                        'id' => 'nationality',
                        'class' => getLoggedInUser()->thememode ? 'bg-light form-select' : 'bg-white form-select',
                        'tabindex' => '21',
                        'autocomplete' => 'off',
                        'data-control' => 'select2',
                        'placeholder' => __('messages.select_country'),
                    ]
                ) }}
            </div>
        </div>

        <div style="display:none">
        <div class="col-md-3">
            <div class="form-group mb-5">
                {{ Form::label('password', __('messages.user.password').':', ['class' => 'form-label']) }}
                {{-- <span class="required"></span> --}}
                {{ Form::password('password', ['class' => 'form-control','min' => '6','max' => '10', 'tabindex' => '10','placeholder'=>__('messages.user.password')]) }}
            </div>
        </div>
        <div class="col-md-3">
            <div class="form-group mb-5">
                {{ Form::label('password_confirmation', __('messages.user.password_confirmation').':', ['class' => 'form-label']) }}
                {{-- <span class="required"></span> --}}
                {{ Form::password('password_confirmation', ['class' => 'form-control','min' => '6','max' => '10', 'tabindex' => '11','placeholder'=>__('messages.user.password_confirmation')]) }}
            </div>
        </div>

        <div class="col-md-3">
            <div class="form-group mb-5">
                {{ Form::label('charge_id', __('messages.charges').':', ['class' => 'form-label']) }}
                <span class="required"></span>
                {{ Form::select('charge_id', $charges, null, ['class' => 'form-select', 'id' => 'patientCharge', 'placeholder' => __('messages.common.choose') . ' ' . __('messages.charges'), 'data-control' => 'select2', 'tabindex' => "9"]) }}
            </div>
        </div>
        <div class="col-md-3">
            <div class="form-group mb-5">
                {{ Form::label('date', __('messages.case.case_date') . ':', ['class' => 'form-label']) }}
                <span class="required"></span>
                {{ Form::text('date', null, ['placeholder' => __('messages.case.case_date') ,'id' => 'opdDiagnosisReportDate', 'auto', 'class' => getLoggedInUser()->thememode ? 'bg-light form-control' : 'bg-white form-control', 'required', 'autocomplete' => 'off']) }}
            </div>
        </div>
        </div>
        <div class="form-group col-md-3">
            <div class="row2" io-image-input="true">
                {{ Form::label('image',__('messages.common.profile').(':'), ['class' => 'form-label']) }}
                <div class="d-block">
                    <div class="image-picker">
                        <div class="image previewImage" id="editPatientPreviewImage"
                             style="background-image: url({{ isset($patient->patientUser->media[0]) ? $patient->patientUser->image_url : asset('assets/img/avatar.png') }})">
                        <span class="picker-edit rounded-circle text-gray-500 fs-small" title="{{ __('messages.common.profile') }}">
                            <label>
                                <i class="fa-solid fa-pen" id="profileImageIcon"></i>
                                <input type="file" id="editAccountantProfileImage" name="image"
                                       class="image-upload d-none profileImage editPatientImage"
                                       accept=".png, .jpg, .jpeg, .gif"/>
                                <input type="hidden" name="avatar_remove"/>
                            </label>
                        </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="form-group mb-5">
                {{ Form::label('status', __('messages.common.status').':', ['class' => 'form-label']) }}
                <div class="form-check form-switch form-check-custom">
                    <input class="form-check-input w-35px h-20px is-active" name="status" type="checkbox" value="1"
                           tabindex="8" {{ ($patient->patientUser->status === 1) ? 'checked' : '' }}>
                </div>
            </div>
        </div>
    </div>
    {{-- <div class="row mt-3 mb-5">
        <div class="col-md-12 mb-3">
            <h5>{{ __('messages.setting.social_details') }}</h5>
        </div>

        <!-- Facebook URL Field -->
        <div class="form-group col-sm-6 mb-5">
            {{ Form::label('facebook_url', __('messages.facebook_url').':', ['class' => 'form-label']) }}
            {{ Form::text('facebook_url', null, ['class' => 'form-control patientFacebookUrl','id'=>'editPatientFacebookUrl', 'onkeypress' => 'return avoidSpace(event);','placeholder'=>__('messages.facebook_url')]) }}
        </div>

        <!-- Twitter URL Field -->
        <div class="form-group col-sm-6 mb-5">
            {{ Form::label('twitter_url', __('messages.twitter_url').':', ['class' => 'form-label']) }}
            {{ Form::text('twitter_url', null, ['class' => 'form-control patientTwitterUrl','id'=>'editPatientTwitterUrl', 'onkeypress' => 'return avoidSpace(event);','placeholder'=>__('messages.twitter_url')]) }}
        </div>

        <!-- Instagram URL Field -->
        <div class="form-group col-sm-6 mb-5">
            {{ Form::label('instagram_url', __('messages.instagram_url').':', ['class' => 'form-label']) }}
            {{ Form::text('instagram_url', null, ['class' => 'form-control patientInstagramUrl', 'id'=>'editPatientInstagramUrl', 'onkeypress' => 'return avoidSpace(event);','placeholder'=>__('messages.instagram_url')]) }}
        </div>

        <!-- LinkedIn URL Field -->
        <div class="form-group col-sm-6 mb-5">
            {{ Form::label('linkedIn_url', __('messages.linkedIn_url').':', ['class' => 'form-label']) }}
            {{ Form::text('linkedIn_url', null, ['class' => 'form-control patientLinkedInUrl','id'=>'editPatientLinkedInUrl', 'onkeypress' => 'return avoidSpace(event);','placeholder'=>__('messages.linkedIn_url')]) }}
        </div>

    </div> --}}

    <div class="d-flex justify-content-end">
        {{ Form::submit(__('messages.common.save'), ['class' => 'btn btn-primary me-2','id' => 'editPatientSave']) }}
        <a href="{{ route('patients.index') }}"
           class="btn btn-secondary me-2">{!! __('messages.common.cancel') !!}</a>
    </div>
</div>
<script>
    $(document).ready(function () {
        function handlePatientTypeChange() {
            if ($("#patient_type").val() === 'company') {
                $.ajax({
                    type: "GET",
                    url: "/get-companies",
                    success: function (response) {
                        const selectedId = @json($patient->company_id);
                        $("#company_info").show();
                        let output = '';
                        for (let i = 0; i < response.length; i++) {
                            const item = response[i];
                            const selected = String(item.id) === String(selectedId) ? ' selected' : '';
                            output += `<option value="${item.id}"${selected}>${item.name}</option>`;
                        }
                        $("#company_id").html(output);
                    }
                });
            } else {
                $("#company_id").html(null);
                $('#staff_id').val(null);
                $("#company_info").hide();
            }
        }
    
        handlePatientTypeChange();
    
        $("#patient_type").on('change', handlePatientTypeChange);
    });
    // $(document).ready(function () {
    //     $("#patient_type").on('click', function () {
    //         if ($(this).val() === 'company') {
    //             $.ajax({
    //                 type: "GET",
    //                 url: "/get-companies",
    //                 success: function (response) {
    //                     $("#company_info").show()
    //                     let output = ''
    //                     for (let i=0;i<response.length;i++)
    //                     {
    //                         output+=`<option value="${response[i].id}">${response[i].name}</option>`
    //                     }
    //                     $("#company_id").html(output);
    //                 }
    //             })
    //         }
    //         else{
    //             $("#company_info").hide()
    //         }
    //     })
    // })
</script>
