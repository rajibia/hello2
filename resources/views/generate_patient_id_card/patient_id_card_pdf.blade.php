<!DOCTYPE html>
<html lang="en">

<head>
    <meta http-equiv="Content-Type" content="text/html;charset=UTF-8">
    @php
        $settingValue = getSettingValue();
        \Carbon\Carbon::setlocale(config('app.locale'));
    @endphp
    <title>{{ __('messages.patient_id_card.patient_id_card') }}</title>
    <style>
        * {
            font-family: "Segoe UI", Tahoma, Geneva, Verdana, sans-serif;
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        @page {
            margin: 15mm !important;
            size: A4 portrait;
        }

        body {
            margin: 0;
            padding: 0;
            background: white;
        }

        .card-wrapper {
            width: 100%;
            max-width: 650px;
            margin: 20px auto;
            background: white;
            border-radius: 12px;
            box-shadow: 0 7px 29px rgba(100, 100, 111, 0.2);
            overflow: hidden;
            page-break-after: avoid;
        }

        .card-header {
            background-color: {{ !empty($patientIdCardTemplateData->color) ? $patientIdCardTemplateData->color : '#8B6914' }};
            padding: 18px 20px;
            display: table;
            width: 100%;
            border-radius: 12px 12px 0 0;
        }

        .header-logo {
            display: table-cell;
            vertical-align: middle;
            width: auto;
            padding-right: 15px;
        }

        .header-logo img {
            width: 50px;
            height: 50px;
            object-fit: contain;
            display: block;
        }

        .header-title {
            display: table-cell;
            vertical-align: middle;
            width: auto;
            padding-right: 30px;
        }

        .header-title h3 {
            color: white;
            font-weight: 700;
            margin: 0;
            font-size: 18px;
            letter-spacing: 0.5px;
        }

        .header-address {
            display: table-cell;
            vertical-align: middle;
            text-align: right;
            width: auto;
            padding-left: 20px;
        }

        .header-address p {
            color: white;
            font-size: 11px;
            margin: 0;
            line-height: 1.4;
            font-weight: 500;
        }

        .card-body {
            padding: 20px;
            display: table;
            width: 100%;
            border-bottom: 1px solid #e0e0e0;
            min-height: 180px;
        }

        .patient-avatar-section {
            display: table-cell;
            vertical-align: top;
            width: 140px;
            text-align: center;
            padding-right: 20px;
            border-right: 1px solid #f0f0f0;
        }

        .patient-avatar {
            width: 110px;
            height: 110px;
            border-radius: 50%;
            margin: 0 auto 10px;
            background: #f5f5f5;
            overflow: hidden;
            border: 3px solid #e0e0e0;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .patient-avatar img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .patient-details-section {
            display: table-cell;
            vertical-align: top;
            padding: 0 20px;
        }

        .patient-details-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 12px;
        }

        .patient-details-table tr {
            border-bottom: 1px solid #f5f5f5;
        }

        .patient-details-table tr:last-child {
            border-bottom: none;
        }

        .patient-details-table td {
            padding: 5px 0;
            vertical-align: middle;
            line-height: 1.4;
        }

        .patient-details-table td:first-child {
            font-weight: 600;
            color: #333;
            width: 100px;
            padding-right: 10px;
            white-space: nowrap;
        }

        .patient-details-table td:last-child {
            color: #555;
            word-break: break-word;
        }

        .qr-code-section {
            display: table-cell;
            vertical-align: top;
            width: 130px;
            text-align: center;
            padding-left: 20px;
        }

        .qr-code-box {
            background: white;
            padding: 10px;
            border-radius: 8px;
            display: inline-block;
        }

        .qr-code-box img {
            width: 100px;
            height: 100px;
            display: block;
        }

        .patient-id-label {
            margin-top: 10px;
            font-weight: 700;
            font-size: 13px;
            color: #2196F3;
            word-break: break-all;
            letter-spacing: 0.5px;
        }

        .card-footer {
            padding: 12px 20px;
            background: #fafafa;
            text-align: center;
            font-size: 10px;
            color: #999;
            border-top: 1px solid #e0e0e0;
        }

        .no-photo {
            width: 110px;
            height: 110px;
            border-radius: 50%;
            background: #e0e0e0;
            display: flex;
            align-items: center;
            justify-content: center;
            border: 3px solid #e0e0e0;
            margin: 0 auto 10px;
            color: #999;
            font-size: 11px;
        }
    </style>
</head>

<body>
    <div class="card-wrapper">
        <!-- Header Section -->
        <div class="card-header">
            <div class="header-logo">
                <img src="{{ asset($settingValue['app_logo']['value']) }}" alt="Hospital Logo" />
            </div>
            <div class="header-title">
                <h3>{{ getAppName() }}</h3>
            </div>
            <div class="header-address">
                <p>{{ $settingValue['hospital_address']['value'] }}</p>
            </div>
        </div>

        <!-- Card Body Section -->
        <div class="card-body">
            <!-- Patient Avatar -->
            <div class="patient-avatar-section">
                <div class="patient-avatar">
                    @if(!empty($data['profile']))
                        <img src="data:image/png;base64,{{ $data['profile'] }}" alt="Patient Photo" />
                    @else
                        <div class="no-photo">No Photo</div>
                    @endif
                </div>
            </div>

            <!-- Patient Details -->
            <div class="patient-details-section">
                <table class="patient-details-table">
                    @if (!empty($patientIdCardData->patientUser->full_name))
                        <tr>
                            <td>{{ __('messages.bill.patient_name') }}:</td>
                            <td><strong>{{ $patientIdCardData->patientUser->full_name }}</strong></td>
                        </tr>
                    @endif
                    @if (!empty($patientIdCardData->patientUser->email) && $patientIdCardData->idCardTemplate->email)
                        <tr>
                            <td>{{ __('auth.email') }}:</td>
                            <td>{{ $patientIdCardData->patientUser->email }}</td>
                        </tr>
                    @endif
                    @if (!empty($patientIdCardData->patientUser->phone) && $patientIdCardData->idCardTemplate->phone)
                        <tr>
                            <td>{{ __('messages.sms.phone_number') }}:</td>
                            <td>{{ $patientIdCardData->patientUser->phone }}</td>
                        </tr>
                    @endif
                    @if (!empty($patientIdCardData->patientUser->dob) && $patientIdCardData->idCardTemplate->dob)
                        <tr>
                            <td>{{ __('messages.user.dob') }}:</td>
                            <td>{{ $patientIdCardData->patientUser->dob }}</td>
                        </tr>
                    @endif
                    @if (!empty($patientIdCardData->patientUser->blood_group) && $patientIdCardData->idCardTemplate->blood_group)
                        <tr>
                            <td>{{ __('messages.user.blood_group') }}:</td>
                            <td>{{ $patientIdCardData->patientUser->blood_group }}</td>
                        </tr>
                    @endif
                    @if (!empty($patientIdCardData->address) && $patientIdCardData->idCardTemplate->address)
                        <tr>
                            <td>{{ __('messages.common.address') }}:</td>
                            <td>{{ $patientIdCardData->address->address1 }}</td>
                        </tr>
                    @endif
                </table>
            </div>

            <!-- QR Code Section -->
            <div class="qr-code-section">
                <div class="qr-code-box">
                    <img src="data:image/png;base64,{!! base64_encode($qrCode) !!}" alt="QR Code" />
                </div>
                @if (!empty($patientIdCardData->patient_unique_id))
                    <div class="patient-id-label">{{ $patientIdCardData->patient_unique_id }}</div>
                @endif
            </div>
        </div>

        <!-- Footer Section -->
        <div class="card-footer">
            <p>{{ __('messages.patient_id_card.patient_id_card') }} | Generated: {{ now()->format('Y-m-d') }}</p>
        </div>
    </div>
</body>

</html>

