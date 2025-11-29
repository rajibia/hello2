<div class="alert alert-danger d-none" id="patientErrorBox"></div>
<div class="row gx-10 mb-5">
    <div class="col-md-12 mb-3">
        <h4>{{ __('messages.user.personal_information') }}</h4>
    </div>
    <div class="col-md-3">
        <div class="form-group mb-5">
            {{ Form::label('first_name', __('messages.user.first_name').':', ['class' => 'form-label']) }}
            <span class="required"></span>
            {{ Form::text('first_name', null, ['class' => 'form-control', 'required', 'id' => 'patientFirstName','tabindex' => '1','placeholder'=>__('messages.user.first_name')]) }}
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
            {{ Form::text('dob', null, ['class' => (getLoggedInUser()->thememode ? 'bg-light patientBirthDate form-control' : 'bg-white patientBirthDate form-control'), 'id' => 'patientBirthDate', 'autocomplete' => 'off', 'tabindex' => '3','placeholder'=>__('messages.user.dob')]) }}
        </div>
        <div class="form-group mb-5">
            {{ Form::label('age', __('messages.age').':', ['class' => 'form-label']) }}
            {{ Form::number('age_new', null, ['class' => (getLoggedInUser()->thememode ? 'bg-light form-control' : 'bg-white form-control'), 'id' => 'patientAge', 'autocomplete' => 'off', 'tabindex' => '3','placeholder'=>__('messages.age')]) }}
        </div>
    </div>
    <div class="col-md-3">
        <div class="form-group mobile-overlapping  mb-5">
            {{ Form::label('phone', __('messages.user.phone').':', ['class' => 'form-label']) }}
            <span class="required"></span><br>
            {{ Form::tel('phone', getCountryCode(), ['class' => 'form-control phoneNumber', 'id' => 'patientPhoneNumber', 'required', 'onkeyup' => 'if (/\D/g.test(this.value)) this.value = this.value.replace(/\D/g,"")', 'tabindex' => '4']) }}
            {{ Form::hidden('prefix_code',null,['class'=>'prefix_code']) }}
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
                {{-- {{ Form::radio('gender', '0', true, ['class' => 'form-check-input', 'tabindex' => '6','id'=>'patientMale']) }} &nbsp;
                <label class="form-label">{{ __('messages.user.female') }}</label>
                {{ Form::radio('gender', '1', false, ['class' => 'form-check-input', 'tabindex' => '7','id'=>'patientFemale']) }} --}}
                {{ Form::radio('gender', '0', true, ['class' => 'form-check-input', 'tabindex' => '5','id'=>'patientMale']) }} &nbsp;
                <label class="form-label">{{ __('messages.user.female') }}</label>
                {{ Form::radio('gender', '1', false, ['class' => 'form-check-input', 'tabindex' => '6','id'=>'patientFemale']) }}

            </span>
        </div>
    </div>
    <div class="col-md-3">
        <div class="form-group mb-5">
            {{ Form::label('blood_group', __('messages.user.blood_group').':', ['class' => 'form-label']) }}
            {{ Form::select('blood_group', $bloodGroup, null, ['class' => 'form-select', 'id' => 'patientCharges', 'placeholder' => __('messages.user.select_blood_group'), 'data-control' => 'select2', 'tabindex' => "7"]) }}
        </div>
    </div>
    <div class="col-md-3">
        <div class="form-group mb-5">
            {{ Form::label('insurance_number', __('messages.insurance_number'), ['class' => 'form-label']) }}
            {{ Form::text('insurance_number', null, ['class' => 'form-control','placeholder'=>__('messages.insurance_number'), 'tabindex' => "8"]) }}
        </div>
    </div>
    <div class="col-md-3">
        <div class="form-group mb-5">
            {{ Form::label('patient_type', 'Type', ['class' => 'form-label']) }}
            <span class="required"></span>
            {{ Form::select('patient_type', ['company' => 'Company', 'individual' => 'Individual'], null, [
            'class' => 'form-select',
            'id' => 'patient_type',
            'placeholder' => 'Select Type',
            'tabindex' => "9"
            ]) }}
        </div>
    </div>

    <div class="row col-md-6" id="company_info" style="display:none">
        <div class="col-md-3">
            <div class="form-group mb-5">
                {{ Form::label('company_id', 'Company', ['class' => 'form-label', 'tabindex' => "10"]) }}
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
                {{ Form::text('staff_id', null, [
                    'class' => 'form-control',
                    'id' => 'staff_id',
                    'placeholder' => 'Staff Id'
                ]) }}
            </div>
        </div>
    </div>
    
    {{-- <div class="col-md-6">
        <div class="form-group mb-5">
            {{ Form::label('doctor_name', __('messages.case.doctor') . ':', ['class' => 'form-label']) }}
            <span class="required"></span>
            {{ Form::select('doctor_id', $doctors, null, ['class' => 'form-select select2Selector', 'required', 'id' => 'caseDoctorId', 'placeholder' => __('messages.web_home.select_doctor'), 'data-control' => 'select2', 'required']) }}
        </div>
    </div> --}}
    <hr>
    <div class="row mt-3 mb-5">
        <div class="col-md-12 mb-3">
            <h4>{{ __('messages.user.emergency_contact') }}</h4>
        </div>
        <div class="col-md-4">
            <div class="form-group mb-5">
                {{ Form::label('guardian_name', __('messages.user.name').':', ['class' => 'form-label']) }}
                
                {{ Form::text('guardian_name', null, ['class' => 'form-control', 'id' => 'guardianName','tabindex' => '11','placeholder'=>__('messages.user.name')]) }}
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group mobile-overlapping  mb-5">
                {{ Form::label('guardian_phone', __('messages.user.phone').':', ['class' => 'form-label']) }}
                
                {{ Form::tel('guardian_phone', getCountryCode(), ['class' => 'form-control phoneNumber', 'id' => 'guardianPhoneNumber', 'onkeyup' => 'if (/\D/g.test(this.value)) this.value = this.value.replace(/\D/g,"")', 'tabindex' => '12']) }}
                {{ Form::hidden('prefix_code',null,['class'=>'prefix_code']) }}
                <span class="text-success valid-msg d-none fw-400 fs-small mt-2">✓ &nbsp; {{__('messages.valid')}}</span>
                <span class="text-danger error-msg d-none fw-400 fs-small mt-2"></span>
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group mb-5">
                {{ Form::label('guardian_relation', __('messages.user.relation').':', ['class' => 'form-label']) }}
                
                {{ Form::text('guardian_relation', null, ['class' => 'form-control', 'id' => 'guardianRelation','tabindex' => '13','placeholder'=>__('messages.user.guardian_relation_placeholder')]) }}
            </div>
        </div>
    </div>
<hr>
<div class="row mt-3 mb-5">
    <div class="col-md-12 mb-3">
        <h4>{{ __('messages.user.address_details') }}</h4>
    </div>
    <div class="col-md-4">
        <div class="form-group mb-5">
            {{ Form::label('address1', __('messages.user.address1').':', ['class' => 'form-label']) }}
            {{ Form::text('address1', null, ['class' => 'form-control','tabindex' => '14','placeholder'=>__('messages.user.address1')]) }}
        </div>
    </div>
    <div class="col-md-4">
        <div class="form-group mb-5">
            {{ Form::label('address2', __('messages.user.address2').':', ['class' => 'form-label']) }}
            {{ Form::text('address2', null, ['class' => 'form-control','tabindex' => '15','placeholder'=>__('messages.user.address2')]) }}
        </div>
    </div>
    <div class="col-md-4">
        <div class="form-group mb-5">
            {{ Form::label('city', __('messages.user.city').':', ['class' => 'form-label']) }}
            {{ Form::text('city', null, ['class' => 'form-control','tabindex' => '16','placeholder'=>__('messages.user.city')]) }}
        </div>
    </div>
    <div class="col-md-4">
        <div class="form-group mb-5">
            {{ Form::label('zip', __('messages.user.zip').':', ['class' => 'form-label']) }}
            {{ Form::text('zip', null, ['class' => 'form-control','tabindex' => '17','placeholder'=>__('messages.user.zip')]) }}
             <!--,'minlength' => '6','maxlength' => '6',-->
        </div>
    </div>
    <div class="col-md-4">
        <div class="form-group mb-5">
            {{ Form::label('location', __('messages.location').':', ['class' => 'form-label']) }}
            {{ Form::text('location', null, ['class' => 'form-control','tabindex' => '18', 'placeholder'=>__('messages.location')]) }}
        </div>
    </div>
        <div class="col-md-4">
        <div class="form-group mb-5">
            {{ Form::label('email', __('messages.user.email').':', ['class' => 'form-label']) }}
            {{-- <span class="required"></span> --}}
            {{ Form::text('email', null, ['class' => 'form-control','tabindex' => '19', 'placeholder' => __('messages.user.email'), 'autocomplete' => 'off']) }}
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
            {{ Form::text('occupation', null, ['placeholder' => __('messages.case.occupation'),'tabindex' => '20' ,'id' => 'occupation', 'auto', 'class' => getLoggedInUser()->thememode ? 'bg-light form-control' : 'bg-white form-control', 'autocomplete' => 'off']) }}
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
                null,
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
    <div class="col-md-3">
        <div class="form-group mb-5">
            {{ Form::label('password', __('messages.user.password').':', ['class' => 'form-label']) }}
            {{-- <span class="required"></span> --}}
            {{ Form::password('password', ['class' => 'form-control','min' => '6','max' => '10', 'tabindex' => '22','placeholder'=>__('messages.user.password'), 'autocomplete' => 'off']) }}
        </div>
    </div>
    <div class="col-md-3">
        <div class="form-group mb-5">
            {{ Form::label('password_confirmation', __('messages.user.password_confirmation').':', ['class' => 'form-label']) }}
            {{-- <span class="required"></span> --}}
            {{ Form::password('password_confirmation', ['class' => 'form-control','min' => '6','max' => '10', 'tabindex' => '23','placeholder'=>__('messages.user.password_confirmation')]) }}
        </div>
    </div>
     
    <div class="col-md-3">
        <div class="form-group mb-5">
            {{ Form::label('charge_id', __('messages.charges').':', ['class' => 'form-label']) }}
            <!--<span class="required"></span>-->
            {{ Form::select('charge_id', $charges, null, ['class' => 'form-select', 'id' => 'patientCharge', 'placeholder' => __('messages.common.choose') . ' ' . __('messages.charges'), 'data-control' => 'select2', 'tabindex' => "24"]) }}
        </div>
    </div>
    <div class="col-md-3">
        <div class="form-group mb-5">
            {{ Form::label('date', __('messages.case.case_date') . ':', ['class' => 'form-label']) }}
            <!--<span class="required"></span>-->
            {{ Form::text('date', null, ['placeholder' => __('messages.case.case_date'),'tabindex' => '25' ,'id' => 'opdDiagnosisReportDate', 'auto', 'class' => getLoggedInUser()->thememode ? 'bg-light form-control' : 'bg-white form-control', 'autocomplete' => 'off']) }}
        </div>
    </div>

    <div class="form-group col-md-4 mb-5">
        <div class="row2" io-image-input="true">
            {{ Form::label('image',__('messages.common.profile').(':'), ['class' => 'form-label']) }}
            <div class="d-block">
                <?php
                $style = 'style=';
                $background = 'background-image:';
                ?>

                <div class="image-picker">
                    <div class="image previewImage" id="patientPreviewImage"
                        {{$style}}"{{$background}} url({{ asset('assets/img/avatar.png')}}">
                        <span class="picker-edit rounded-circle text-gray-500 fs-small" title="{{ __('messages.common.profile') }}">
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
    <button type="button" class="btn btn-sm btn-primary mb-2" data-bs-toggle="modal" data-bs-target="#webcamModal">
        Open Camera
    </button>

    <!-- Webcam Modal -->
    <div class="modal fade" id="webcamModal" tabindex="-1" aria-labelledby="webcamModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Capture Image</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body text-center">
                    <video id="webcam" autoplay playsinline width="100%" height="auto" style="border-radius: 8px;"></video>
                    <canvas id="canvas" class="d-none"></canvas>
                    <br/>
                    <button type="button" class="btn btn-success mt-3" id="captureBtn">Capture</button>
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
                     checked>
            </div>
        </div>
    </div>
</div>
<div class="row mt-3 mb-5" style="display: none">
    <div class="col-md-12 mb-3">
        <h5>{{ __('messages.setting.social_details') }}</h5>
    </div>

    <!-- Facebook URL Field -->
    <div class="form-group col-sm-6 mb-5">
        {{ Form::label('facebook_url', __('messages.facebook_url').':', ['class' => 'form-label']) }}
        {{ Form::text('facebook_url', null, ['class' => 'form-control patientFacebookUrl','id'=>'patientFacebookUrl', 'onkeypress' => 'return avoidSpace(event);','placeholder'=>__('messages.facebook_url')]) }}
    </div>

    <!-- Twitter URL Field -->
    <div class="form-group col-sm-6 mb-5">
        {{ Form::label('twitter_url', __('messages.twitter_url').':', ['class' => 'form-label']) }}
        {{ Form::text('twitter_url', null, ['class' => 'form-control patientTwitterUrl','id'=>'patientTwitterUrl', 'onkeypress' => 'return avoidSpace(event);','placeholder'=>__('messages.twitter_url')]) }}
    </div>

    <!-- Instagram URL Field -->
    <div class="form-group col-sm-6 mb-5">
        {{ Form::label('instagram_url', __('messages.instagram_url').':', ['class' => 'form-label']) }}
        {{ Form::text('instagram_url', null, ['class' => 'form-control patientInstagramUrl', 'id'=>'patientInstagramUrl', 'onkeypress' => 'return avoidSpace(event);','placeholder'=>__('messages.instagram_url')]) }}
    </div>

    <!-- LinkedIn URL Field -->
    <div class="form-group col-sm-6 mb-5">
        {{ Form::label('linkedIn_url', __('messages.linkedIn_url').':', ['class' => 'form-label']) }}
        {{ Form::text('linkedIn_url', null, ['class' => 'form-control patientLinkedInUrl','id'=>'patientLinkedInUrl', 'onkeypress' => 'return avoidSpace(event);','placeholder'=>__('messages.linkedIn_url')]) }}
    </div>

</div>
<div class="d-flex justify-content-end">
    {{ Form::submit(__('messages.common.save'), ['class' => 'btn btn-primary me-2','id' => 'patientSave']) }}
    <a href="{{ route('patients.index') }}"
       class="btn btn-secondary me-2">{!! __('messages.common.cancel') !!}</a>
</div>
<script>
    $(document).ready(function () {
        $("#patient_type").on('click', function () {
            if ($(this).val() === 'company') {
                $.ajax({
                    type: "GET",
                    url: "/get-companies",
                    success: function (response) {
                        $("#company_info").show()
                        let output = ''
                        for (let i=0;i<response.length;i++)
                        {
                            output+=`<option value="${response[i].id}">${response[i].name}</option>`
                        }
                        $("#company_id").html(output);
                    }
                })
            }
            else{
                 $("#company_id").html(null);
                $('#staff_id').val(null);
                $("#company_info").hide()
            }
        })
    })
</script>

<script>
    document.addEventListener("DOMContentLoaded", function () {
        const video = document.getElementById('webcam');
        const canvas = document.getElementById('canvas');
        const captureBtn = document.getElementById('captureBtn');
        const previewImage = document.getElementById('patientPreviewImage');

        let stream = null;

        // Start camera when modal opens
        document.getElementById('webcamModal').addEventListener('shown.bs.modal', async () => {
            try {
                stream = await navigator.mediaDevices.getUserMedia({ video: true });
                video.srcObject = stream;
            } catch (err) {
                alert("Cannot access webcam: " + err.message);
            }
        });

        // Stop camera when modal closes
        document.getElementById('webcamModal').addEventListener('hidden.bs.modal', () => {
            if (stream) {
                stream.getTracks().forEach(track => track.stop());
            }
        });

        // Capture image
        captureBtn.addEventListener('click', () => {
            const context = canvas.getContext('2d');
            canvas.width = video.videoWidth;
            canvas.height = video.videoHeight;
            context.drawImage(video, 0, 0, canvas.width, canvas.height);

            const imageData = canvas.toDataURL('image/png');
            previewImage.setAttribute('style', `background-image: url(${imageData})`);

            // Store it in a hidden input to submit with the form
            let existingInput = document.querySelector('input[name="image_base64"]');
            if (!existingInput) {
                existingInput = document.createElement('input');
                existingInput.type = 'hidden';
                existingInput.name = 'image_base64';
                document.querySelector('form').appendChild(existingInput);
            }
            existingInput.value = imageData;

            // Close modal
            const modal = bootstrap.Modal.getInstance(document.getElementById('webcamModal'));
            modal.hide();
        });
    });
</script>