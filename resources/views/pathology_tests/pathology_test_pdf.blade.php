<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "//www.w3.org/TR/html4/strict.dtd">
<html lang="en">

<head>
    <meta http-equiv="Content-Type" content="text/html;charset=UTF-8">
    <title>Laboratory Test Report - {{ $pathologyTest->bill_no }}</title>
    <style>
        * {
            font-family: DejaVu Sans, Arial, "Helvetica", Arial, "Liberation Sans", sans-serif;
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: "Lato", sans-serif;
            padding: 20px;
            font-size: 12px;
            line-height: 1.4;
            background: #f8fafc;
            color: #333;
        }

        .pathology-report-container {
            max-width: 800px;
            margin: 0 auto;
            background: white;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }

        .pathology-report-header {
            text-align: center;
            margin-bottom: 0;
            padding: 25px 20px;
            border-bottom: 2px solid #e5e7eb;
            background: white;
            width: 100%;
        }

        .pathology-report-header .d-flex {
            display: flex !important;
            justify-content: space-between !important;
            align-items: center !important;
            margin-bottom: 15px !important;
        }

        .pathology-report-header .text-center {
            text-align: center !important;
            flex-grow: 1 !important;
        }

        .pathology-report-header .me-3 {
            margin-right: 12px !important;
        }

        .pathology-report-header .no-print {
            display: none !important;
        }

        .pathology-report-title {
            color: #1f2937;
            font-weight: 700;
            font-size: 18px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin: 0;
        }

        .pathology-report-subtitle {
            color: #1e40af;
            font-weight: 600;
            font-size: 16px;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin: 0;
        }

        .pathology-report-body {
            padding: 20px 25px;
            background: white;
        }

        .pathology-admin-details {
            margin-bottom: 25px;
            width: 100%;
        }

        .pathology-admin-label {
            font-weight: 600;
            font-size: 12px;
            text-transform: uppercase;
            letter-spacing: 0.3px;
        }

        .admin-row {
            page-break-inside: avoid;
            margin-bottom: 8px;
            display: flex !important;
            flex-wrap: wrap !important;
        }

        .admin-row-yellow {
            background: #fef3c7 !important;
            background-color: #fef3c7 !important;
            padding: 8px 12px;
            border-radius: 4px;
            margin: 0;
        }

        .admin-row-white {
            background: white !important;
            background-color: white !important;
            padding: 8px 12px;
            border-radius: 4px;
            border: 1px solid #e5e7eb;
            margin: 0;
        }

        .admin-field {
            margin-bottom: 8px;
            display: block;
            width: 100%;
            line-height: 1.4;
        }

        .admin-field:last-child {
            margin-bottom: 0;
        }

        .col-4 {
            flex: 0 0 33.333333% !important;
            max-width: 33.333333% !important;
            padding: 0 6px !important;
        }

        .col-6 {
            flex: 0 0 50% !important;
            max-width: 50% !important;
            padding: 0 6px !important;
        }

        .text-end {
            text-align: right !important;
        }

        .text-center {
            text-align: center !important;
        }

        .admin-field .admin-label {
            display: inline-block;
            width: 100%;
        }

        .admin-field-indented {
            margin-left: 20px;
        }

        .admin-label {
            font-weight: 600;
            font-size: 12px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .admin-label-yellow {
            color: #92400e;
        }

        .admin-label-white {
            color: #374151;
        }

        .pathology-results-section {
            background: white;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
            overflow: hidden;
        }

        .pathology-results-header {
            background: #fbbf24;
            padding: 10px 20px;
            text-align: center;
            border-bottom: 2px solid #f59e0b;
        }

        .pathology-results-header h6 {
            color: #202020;
            font-weight: 700;
            font-size: 14px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin: 0;
        }

        .pathology-results-table {
            width: 100%;
            border-collapse: collapse;
            background: white;
            table-layout: fixed;
        }

        .pathology-results-table thead tr {
            background: #fef3c7 !important;
            background-color: #fef3c7 !important;
        }

        .pathology-results-table thead th {
            color: #202020 !important;
            font-weight: 700 !important;
            font-size: 12px !important;
            text-transform: uppercase !important;
            letter-spacing: 0.5px !important;
            padding: 8px !important;
            text-align: center !important;
            border: 1px solid #d1d5db !important;
        }

        .pathology-results-table tbody tr {
            border-bottom: 1px solid #e5e7eb;
        }

        .pathology-results-table tbody tr:nth-child(odd) {
            background: #fbbf24 !important;
            background-color: #fbbf24 !important;
        }

        .pathology-results-table tbody tr:nth-child(even) {
            background: white !important;
            background-color: white !important;
        }

        .pathology-results-table td {
            padding: 8px !important;
            font-size: 12px !important;
            border: 1px solid #e5e7eb !important;
            vertical-align: middle !important;
            text-align: left !important;
            width: 100% !important;
        }

        .pathology-flag {
            padding: 2px 6px;
            border-radius: 3px;
            font-size: 10px;
            font-weight: bold;
        }

        .flag-low {
            background: #fef3c7;
            color: #92400e;
        }

        .flag-high {
            background: #fee2e2;
            color: #991b1b;
        }

        .flag-normal {
            background: #d1fae5;
            color: #065f46;
        }

        .signature-section {
            margin-top: 30px;
            padding-top: 20px;
            border-top: 2px solid #e5e7eb;
        }

        .signature-line {
            border-top: 1px solid #374151;
            width: 200px;
            margin: 0 auto 5px auto;
            height: 40px;
        }

        .signature-label {
            font-size: 11px;
            color: #6b7280;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            font-weight: 500;
        }

        .row {
            display: flex;
            flex-wrap: wrap;
            margin: 0 -6px;
        }



        .text-center {
            text-align: center;
        }

        /* Custom CSS for PDF Layout */
        .admin-section {
            margin-bottom: 25px;
            width: 100%;
        }

        .admin-row {
            display: flex;
            flex-wrap: wrap;
            margin-bottom: 8px;
            width: 100%;
        }

        .admin-row-yellow {
            background: #fef3c7;
            padding: 8px 12px;
            border-radius: 4px;
            margin: 0;
        }

        .admin-row-white {
            background: white;
            padding: 8px 12px;
            border-radius: 4px;
            border: 1px solid #e5e7eb;
            margin: 0;
        }

        .admin-col-4 {
            flex: 0 0 33.333333%;
            max-width: 33.333333%;
            padding: 0 6px;
        }

        .admin-col-6 {
            flex: 0 0 50%;
            max-width: 50%;
            padding: 0 6px;
        }

        .admin-text-center {
            text-align: center;
        }

        .admin-text-end {
            text-align: right;
        }

        .admin-label {
            font-weight: 600;
            font-size: 12px;
            text-transform: uppercase;
            letter-spacing: 0.3px;
        }

        .admin-label-yellow {
            color: #92400e;
        }

        .admin-label-white {
            color: #374151;
        }

        .admin-margin-left {
            margin-left: 12px;
        }

        .admin-margin-bottom {
            margin-bottom: 12px;
        }

        /* Pathology Admin Details Custom CSS */
        .pathology-admin-details {
            margin-bottom: 25px;
            width: 100%;
        }

        .admin-detail-row {
            display: flex;
            flex-wrap: wrap;
            margin-bottom: 8px;
            width: 100%;
            min-height: 40px;
            align-items: center;
        }

        .admin-detail-row-yellow {
            background: #fbbf24 !important;
            background-color: #fbbf24 !important;
            padding: 8px 12px;
            border-radius: 4px;
            margin: 0;
        }

        .admin-detail-row-white {
            background: white !important;
            background-color: white !important;
            padding: 8px 12px;
            border-radius: 4px;
            border: 1px solid #e5e7eb;
            margin: 0;
        }

        .admin-detail-col {
            display: flex;
            align-items: center;
        }

        .admin-detail-col-4 {
            flex: 0 0 33.333333%;
            max-width: 33.333333%;
            padding: 0 6px;
        }

        .admin-detail-col-6 {
            flex: 0 0 50%;
            max-width: 50%;
            padding: 0 6px;
        }

        .admin-detail-text-center {
            justify-content: center;
            text-align: center;
        }

        .admin-detail-text-right {
            justify-content: flex-end;
            text-align: right;
        }

        .admin-detail-label {
            font-weight: 600;
            font-size: 12px;
            text-transform: uppercase;
            letter-spacing: 0.3px;
            white-space: nowrap;
        }

        .admin-detail-label-yellow {
            color: #92400e !important;
        }

        .admin-detail-label-white {
            color: #374151 !important;
        }

        .admin-detail-margin-left {
            margin-left: 12px;
        }

        .admin-detail-margin-bottom {
            margin-bottom: 12px;
        }

        @media print {
            body {
                margin: 0;
                padding: 10px;
            }
            .no-print {
                display: none;
            }
            * {
                -webkit-print-color-adjust: exact !important;
                color-adjust: exact !important;
                print-color-adjust: exact !important;
            }
        }
    </style>
</head>

<body>
    <div class="pathology-report-container">
        <!-- Pathology Report Header -->
        <div class="pathology-report-header">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <div class="d-flex align-items-center">
                    <!-- Application Logo from settings -->
                    <div class="me-3">
                        <div style="width: 60px; height: 60px; background: transparent; border: 1px solid #e5e7eb; border-radius: 8px; display: flex; align-items: center; justify-content: center; color: #374151; font-weight: bold; font-size: 10px; text-align: center; overflow: hidden;">
                @php
                    $logoUrl = getLogoUrl();
                    $companyName = getCompanyName();
                @endphp
                            @if($logoUrl && !str_contains($logoUrl, 'default_image.jpg'))
                                <img src="{{ $logoUrl }}" alt="Application Logo" style="width: 100%; height: 100%; object-fit: contain; border-radius: 8px; max-width: 60px; max-height: 60px;">
                            @else
                                <div>
                                    <div style="font-size: 8px; color: #6b7280;">{{ $companyName ? substr($companyName, 0, 4) : 'LOGO' }}</div>
                                    <div style="font-size: 6px; color: #6b7280;">{{ $companyName ? substr($companyName, 4, 4) : 'HERE' }}</div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="text-center flex-grow-1">
                    <h4 class="pathology-report-title mb-0" style="font-size: 18px; font-weight: 700; color: #1e3a8a; text-transform: uppercase; letter-spacing: 0.5px;">
                        {{ getCompanyName() ?? 'CARDINAL NAMDINI MINING LTD, CLINIC LABORATORY' }}
                    </h4>
                    <div style="font-size: 14px; color: #1e3a8a; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px; margin-top: 5px;">
                        HOSPITAL MANAGEMENT SYSTEM
                    </div>
                </div>
                <!-- Print and PDF Buttons - Hidden in PDF -->
                <div class="no-print">
                    <button onclick="printLaboratoryReport()" class="btn btn-success me-2">
                        <i class="fas fa-print"></i> Print
                    </button>
                    <a href="{{ route('pathology.test.pdf', $pathologyTest->id) }}" target="_blank" class="btn btn-outline-success me-2">
                        <i class="fas fa-file-pdf"></i> PDF
                    </a>
                </div>
            </div>
            <div class="text-center">
                <h5 class="pathology-report-subtitle mb-0" style="font-size: 16px; font-weight: 600; color: #1e40af; text-transform: uppercase; letter-spacing: 1px;">
                LABORATORY RESULTS
            </h5>
            </div>
        </div>

        <div class="pathology-report-body">
                <!-- Administrative Details Section -->
        <div style="margin-bottom: 25px; width: 100%;">
            <!-- First Row - Yellow Background -->
            <div style="display: flex !important; flex-wrap: wrap; margin-bottom: 8px; width: 100%; min-height: 40px; align-items: center; background: #fbbf24; padding: 8px 12px; border-radius: 4px; margin: 0;">
                <div style="flex: 0 0 33.333333%; max-width: 33.333333%; padding: 0 6px; display: flex; align-items: center;">
                    <span style="font-weight: 600; font-size: 12px; text-transform: uppercase; letter-spacing: 0.3px; white-space: nowrap; color: #1f2937;">DATE : {{ $pathologyTest->created_at ? $pathologyTest->created_at->format('d/m/Y') : 'N/A' }}</span>
                </div>
                <div style="flex: 0 0 33.333333%; max-width: 33.333333%; padding: 0 6px; display: flex; align-items: center; justify-content: center; text-align: center;">
                    <span style="font-weight: 600; font-size: 12px; text-transform: uppercase; letter-spacing: 0.3px; white-space: nowrap; color: #1f2937;">SPECIMEN : {{ strtoupper($pathologyTest->pathologyTestItems->first()->pathologytesttemplate->test_type ?? 'BLOOD') }}</span>
                </div>
                <div style="flex: 0 0 33.333333%; max-width: 33.333333%; padding: 0 6px; display: flex; align-items: center; justify-content: flex-end; text-align: right;">
                    <span style="font-weight: 600; font-size: 12px; text-transform: uppercase; letter-spacing: 0.3px; white-space: nowrap; color: #1f2937;">LAB NO : {{ $pathologyTest->lab_number ?? $pathologyTest->bill_no ?? 'N/A' }}</span>
                </div>
            </div>

            <!-- Second Row - White Background -->
            <div style="display: flex; flex-wrap: wrap; margin-bottom: 8px; width: 100%; min-height: 40px; align-items: center; background: white; padding: 8px 12px; border-radius: 4px; border: 1px solid #e5e7eb; margin: 0;">
                <div style="flex: 0 0 50%; max-width: 50%; padding: 0 6px; display: flex; align-items: center;">
                    <span style="font-weight: 600; font-size: 12px; text-transform: uppercase; letter-spacing: 0.3px; white-space: nowrap; color: #1f2937;">NAME OF PATIENT : {{ strtoupper($pathologyTest->patient->patientUser->full_name ?? 'N/A') }}</span>
                </div>
                <div style="flex: 0 0 50%; max-width: 50%; padding: 0 6px; display: flex; align-items: center; justify-content: flex-end; text-align: right;">
                    <span style="font-weight: 600; font-size: 12px; text-transform: uppercase; letter-spacing: 0.3px; white-space: nowrap; color: #1f2937;">AGE : {{ \Carbon\Carbon::parse($pathologyTest->patient->patientUser->dob ?? now())->age ?? 'N/A' }} YRS</span>
                    <span style="font-weight: 600; font-size: 12px; text-transform: uppercase; letter-spacing: 0.3px; white-space: nowrap; color: #1f2937; margin-left: 12px;">SEX : {{ $pathologyTest->patient->patientUser->gender ? 'F' : 'M' }}</span>
                </div>
            </div>

            <!-- Third Row - White Background -->
            <div style="display: flex; flex-wrap: wrap; margin-bottom: 8px; width: 100%; min-height: 40px; align-items: center; background: white; padding: 8px 12px; border-radius: 4px; border: 1px solid #e5e7eb; margin: 0;">
                <div style="flex: 0 0 50%; max-width: 50%; padding: 0 6px; display: flex; align-items: center;">
                    <span style="font-weight: 600; font-size: 12px; text-transform: uppercase; letter-spacing: 0.3px; white-space: nowrap; color: #1f2937;">DIAGNOSIS : {{ strtoupper($pathologyTest->diagnosis ?? 'N/A') }}</span>
                </div>
                <div style="flex: 0 0 50%; max-width: 50%; padding: 0 6px; display: flex; align-items: center; justify-content: flex-end; text-align: right;">
                    <span style="font-weight: 600; font-size: 12px; text-transform: uppercase; letter-spacing: 0.3px; white-space: nowrap; color: #1f2937;">TEST REQUESTED :
                        @if($pathologyTest->pathologyTestItems && $pathologyTest->pathologyTestItems->count() > 0)
                            @php
                                $testNames = $pathologyTest->pathologyTestItems->pluck('pathologytesttemplate.test_name')->filter()->toArray();
                            @endphp
                            {{ strtoupper(implode(', ', $testNames)) }}
                        @else
                            NO TESTS
                        @endif
                    </span>
                </div>
            </div>

            <!-- Fourth Row - White Background -->
            <div style="display: flex; flex-wrap: wrap; margin-bottom: 12px; width: 100%; min-height: 40px; align-items: center; background: white; padding: 8px 12px; border-radius: 4px; border: 1px solid #e5e7eb; margin: 0;">
                <div style="flex: 0 0 50%; max-width: 50%; padding: 0 6px; display: flex; align-items: center;">
                    <span style="font-weight: 600; font-size: 12px; text-transform: uppercase; letter-spacing: 0.3px; white-space: nowrap; color: #1f2937;">NAME OF CLINICIAN : {{ strtoupper($pathologyTest->doctor->doctorUser->full_name ?? 'N/A') }}</span>
                </div>
                <div style="flex: 0 0 50%; max-width: 50%; padding: 0 6px; display: flex; align-items: center; justify-content: flex-end; text-align: right;">
                    <span style="font-weight: 600; font-size: 12px; text-transform: uppercase; letter-spacing: 0.3px; white-space: nowrap; color: #1f2937;">TEST PERFORMED BY : {{ strtoupper($pathologyTest->performed_by_user->full_name ?? $pathologyTest->performed_by_user->name ?? 'N/A') }}</span>
                </div>
            </div>
        </div>

        <!-- Test Results Sections for Each Test Item -->
        @if($pathologyTest->pathologyTestItems && $pathologyTest->pathologyTestItems->count() > 0)
            @foreach($pathologyTest->pathologyTestItems as $index => $testItem)
                @php
                    $template = $testItem->pathologytesttemplate ?? null;
                    $formConfig = [];
                    $testResults = [];

                    if ($template) {
                        $formConfig = is_string($template->form_configuration)
                            ? json_decode($template->form_configuration, true) ?? []
                            : ($template->form_configuration ?? []);
                    }

                    if (isset($pathologyTest->test_results) && is_array($pathologyTest->test_results)) {
                    $testResults = $pathologyTest->test_results[$testItem->id] ?? [];
                    }

                    $tableType = $formConfig['table_type'] ?? 'standard';
                    $layoutType = $formConfig['layout_type'] ?? 'single_row';
                    $columnsPerRow = $formConfig['columns_per_row'] ?? 1;
                @endphp

                <div class="pathology-results-section">
                        @if($tableType === 'field_value_multi')
                            <!-- Test Results Header - Outside Table -->
                            <div class="text-center">
                                <h5 style="font-weight: bold; color: #92400e; font-size: 16px; margin: 0; padding: 10px 0;">
                                    TEST RESULTS FOR {{ strtoupper($template ? $template->test_name : 'ROUTINE EXAMINATION') }}
                                </h5>
                            </div>
                        @elseif($tableType !== 'field_value_multi')
                    <div class="pathology-results-header">
                        <h6 class="mb-0">
                            TEST RESULTS FOR {{ strtoupper($template ? $template->test_name : 'TEST') }}
                        </h6>
                    </div>
                    <div style="background: white; padding: 8px 20px; border-bottom: 1px solid #e5e7eb;">
                        <div style="font-size: 12px; color: #374151; font-weight: 500;">
                            SPECIMEN: {{ strtoupper($pathologyTest->pathologyTestItems->first()->pathologytesttemplate->test_type ?? 'BLOOD') }}
                        </div>
                    </div>
                        @endif

                        @if($formConfig)
                            @if($tableType === 'standard')
                                <!-- Standard Template Display -->
                                @php
                                    $fields = [];
                                    if (isset($formConfig['fields']) && is_array($formConfig['fields'])) {
                                        $fields = $formConfig['fields'];
                            }
                        @endphp

                                <div class="table-responsive">
                        <table class="pathology-results-table">
                            <thead>
                                <tr>
                                                <th style="width: 30%; background: #fbbf24; color: #202020; font-weight: 700; font-size: 12px; text-transform: uppercase; letter-spacing: 0.5px; padding: 8px; border: 1px solid #d1d5db;">TEST</th>
                                                <th style="width: 25%; background: #fbbf24; color: #202020; font-weight: 700; font-size: 12px; text-transform: uppercase; letter-spacing: 0.5px; padding: 8px; border: 1px solid #d1d5db;">RESULT</th>
                                        <th style="width: 25%; background: #fbbf24; color: #202020; font-weight: 700; font-size: 12px; text-transform: uppercase; letter-spacing: 0.5px; padding: 8px; border: 1px solid #d1d5db;">REFERENCE RANGE</th>
                                                <th style="width: 10%; background: #fbbf24; color: #202020; font-weight: 700; font-size: 12px; text-transform: uppercase; letter-spacing: 0.5px; padding: 8px; border: 1px solid #d1d5db;">FLAG</th>
                                                <th style="width: 10%; background: #fbbf24; color: #202020; font-weight: 700; font-size: 12px; text-transform: uppercase; letter-spacing: 0.5px; padding: 8px; border: 1px solid #d1d5db;">UNIT</th>
                </tr>
            </thead>
                            <tbody>
                                            @if(count($fields) > 0)
                                                @php
                                                    $rowCount = 0;
                                                @endphp
                                                @foreach($fields as $field)
                                                @php
                                                    $rowCount++;
                                                    $rowClass = ($rowCount % 2 == 0) ? 'background: white;' : 'background: #fef3c7;';
                                                @endphp
                                @php
                                    $result = $testResults[$field['name']] ?? null;
                                    $min = $field['reference_min'] ?? null;
                                    $max = $field['reference_max'] ?? null;
                                    $flag = '';
                                    $flagClass = '';

                                    if ($result !== null && $result !== '' && $min !== null && $max !== null && is_numeric($result)) {
                                        $resultValue = floatval($result);
                                        $minValue = floatval($min);
                                        $maxValue = floatval($max);

                                        if ($resultValue < $minValue) {
                                            $flag = 'LOW';
                                            $flagClass = 'flag-low';
                                        } elseif ($resultValue > $maxValue) {
                                            $flag = 'HIGH';
                                            $flagClass = 'flag-high';
                                        } else {
                                            $flag = 'NORMAL';
                                            $flagClass = 'flag-normal';
                                        }
                                    }
                    @endphp
                    <tr style="{{ $rowClass }}">
                                                    <td style="text-align: left; font-weight: 600; color: #374151; font-size: 12px; text-transform: uppercase; padding: 8px; border: 1px solid #e5e7eb;">{{ strtoupper($field['name']) }}</td>
                                                    <td style="text-align: left; font-weight: 500; color: #374151; font-size: 12px; padding: 8px; border: 1px solid #e5e7eb;">{{ strtoupper($result ?? 'N/A') }}</td>
                                                    <td style="text-align: left; font-weight: 500; color: #374151; font-size: 12px; padding: 8px; border: 1px solid #e5e7eb;">{{ $min ?? 'N/A' }} - {{ $max ?? 'N/A' }}</td>
                                                    <td style="text-align: center; padding: 8px; border: 1px solid #e5e7eb;">
                                                        @if($flag)
                                                            <span class="pathology-flag {{ $flagClass }}">{{ $flag }}</span>
                                        @else
                                                            <span style="color: #6b7280; font-size: 10px;">N/A</span>
                            @endif
                        </td>
                                                    <td style="text-align: left; font-weight: 500; color: #374151; font-size: 12px; text-transform: uppercase; padding: 8px; border: 1px solid #e5e7eb;">{{ strtoupper($field['unit'] ?? 'N/A') }}</td>
                                                </tr>
                                                @endforeach
                                            @else
                                                <tr>
                                                    <td colspan="5" style="text-align: center; color: #6b7280; font-style: italic;">No test fields configured</td>
                                                </tr>
                                            @endif
                                        </tbody>
                                    </table>
                                </div>

                                                                                    @elseif($tableType === 'simple')
                                <!-- Simple Template Display (ANALYTE, RESULTS only) -->
                                @php
                                    $fields = $formConfig['fields'] ?? [];
                                @endphp

                                <div class="table-responsive">
                                    <table class="table table-bordered table-sm" style="font-size: 12px;">
                                        <thead>
                                            <tr style="background-color: #fbbf24; padding: 8px 12px;">
                                                <th style="width: 50%; color: #202020; font-weight: 600; text-transform: uppercase; padding: 8px 12px;">ANALYTE</th>
                                                <th style="width: 50%; color: #202020; font-weight: 600; text-transform: uppercase; padding: 8px 12px;">RESULTS</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @if(count($fields) > 0)
                                                @foreach($fields as $field)
                                                @php
                                                    $result = $testResults[$field['name']] ?? null;
                    @endphp
                    <tr>
                                                    <td style="font-weight: 600; text-align: left; background: #f8f9fa; padding: 8px 12px;">{{ strtoupper($field['label']) }}</td>
                                                    <td style="text-align: center; font-weight: 500; color: #374151; padding: 8px 12px;">
                                        @if($result !== null && $result !== '')
                                            {{ strtoupper($result) }}
                                        @else
                                            <span style="color: #9ca3af;">-</span>
                            @endif
                        </td>
                                                </tr>
                                                @endforeach
                            @else
                                                <tr>
                                                    <td colspan="2" class="text-center" style="padding: 20px; color: #7f8c8d;">
                                                        <!-- No fields configured for this template - left blank for clean report appearance -->
                                                    </td>
                                                </tr>
                            @endif
                                        </tbody>
                                    </table>
                                </div>

                            @elseif($tableType === 'species_dependent')
                                <!-- Species Dependent Template Display -->
                                @php
                                    $speciesConfig = [];
                                    if (isset($formConfig['species_config']) && is_array($formConfig['species_config'])) {
                                        $speciesConfig = $formConfig['species_config'];
                                    }

                                    $results = $speciesConfig['results'] ?? '';
                                    $units = $speciesConfig['units'] ?? '';
                                    $speciesDependencies = $speciesConfig['species_dependencies'] ?? [];
                                    $stageDependencies = $speciesConfig['stage_dependencies'] ?? [];

                                    // Get existing values for this test item
                                    $existingResults = is_array($testResults) ? $testResults : [];
                                    $currentResults = $existingResults['results'] ?? '';
                                    $currentSpecies = $existingResults['species'] ?? '';
                                    $currentStage = $existingResults['stage'] ?? '';
                                    $currentCount = $existingResults['count'] ?? '';
                                    $currentUnit = $existingResults['unit'] ?? '';
                                @endphp

                                <div class="table-responsive">
                                    <table class="pathology-results-table">
                                        <thead>
                                            <tr>
                                                <th style="width: 20%; background: #fbbf24; color: #202020; font-weight: 700; font-size: 12px; text-transform: uppercase; letter-spacing: 0.5px; padding: 8px; border: 1px solid #d1d5db;">RESULTS</th>
                                                <th style="width: 20%; background: #fbbf24; color: #202020; font-weight: 700; font-size: 12px; text-transform: uppercase; letter-spacing: 0.5px; padding: 8px; border: 1px solid #d1d5db;">SPECIES</th>
                                                <th style="width: 20%; background: #fbbf24; color: #202020; font-weight: 700; font-size: 12px; text-transform: uppercase; letter-spacing: 0.5px; padding: 8px; border: 1px solid #d1d5db;">STAGE</th>
                                                <th style="width: 20%; background: #fbbf24; color: #202020; font-weight: 700; font-size: 12px; text-transform: uppercase; letter-spacing: 0.5px; padding: 8px; border: 1px solid #d1d5db;">COUNT</th>
                                                <th style="width: 20%; background: #fbbf24; color: #202020; font-weight: 700; font-size: 12px; text-transform: uppercase; letter-spacing: 0.5px; padding: 8px; border: 1px solid #d1d5db;">UNIT</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr style="background: #fef3c7;">
                                                <td style="text-align: left; font-weight: 500; color: #374151; font-size: 12px; text-transform: uppercase; padding: 8px; border: 1px solid #e5e7eb;">{{ $currentResults ? strtoupper($currentResults) : 'N/A' }}</td>
                                                <td style="text-align: left; font-weight: 500; color: #374151; font-size: 12px; text-transform: uppercase; padding: 8px; border: 1px solid #e5e7eb;">{{ $currentSpecies ? strtoupper($currentSpecies) : 'N/A' }}</td>
                                                <td style="text-align: left; font-weight: 500; color: #374151; font-size: 12px; text-transform: uppercase; padding: 8px; border: 1px solid #e5e7eb;">{{ $currentStage ? strtoupper($currentStage) : 'N/A' }}</td>
                                                <td style="text-align: left; font-weight: 500; color: #374151; font-size: 12px; padding: 8px; border: 1px solid #e5e7eb;">{{ $currentCount ?: 'N/A' }}</td>
                                                <td style="text-align: left; font-weight: 500; color: #374151; font-size: 12px; text-transform: uppercase; padding: 8px; border: 1px solid #e5e7eb;">{{ $currentUnit ? strtoupper($currentUnit) : 'N/A' }}</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>

                            @elseif($tableType === 'specimen')
                                <!-- Specimen Template Display -->
                                @php
                                    $specimenName = $formConfig['specimen_name'] ?? ($template ? $template->test_type : 'SPECIMEN');
                                    $fields = [];
                                    if (isset($formConfig['fields']) && is_array($formConfig['fields'])) {
                                        $fields = $formConfig['fields'];
                                    }
                                @endphp

                                <div class="table-responsive">
                                    <table class="pathology-results-table">
                                        <thead>
                                            <tr>
                                                <th style="width: 20%; background: #fbbf24; color: #202020; font-weight: 700; font-size: 12px; text-transform: uppercase; letter-spacing: 0.5px; padding: 8px; border: 1px solid #d1d5db;">SPECIMEN</th>
                                                <th style="width: 30%; background: #fbbf24; color: #202020; font-weight: 700; font-size: 12px; text-transform: uppercase; letter-spacing: 0.5px; padding: 8px; border: 1px solid #d1d5db;">RESULTS</th>
                                                <th style="width: 25%; background: #fbbf24; color: #202020; font-weight: 700; font-size: 12px; text-transform: uppercase; letter-spacing: 0.5px; padding: 8px; border: 1px solid #d1d5db;">REFERENCE RANGE</th>
                                                <th style="width: 15%; background: #fbbf24; color: #202020; font-weight: 700; font-size: 12px; text-transform: uppercase; letter-spacing: 0.5px; padding: 8px; border: 1px solid #d1d5db;">FLAG</th>
                                                <th style="width: 10%; background: #fbbf24; color: #202020; font-weight: 700; font-size: 12px; text-transform: uppercase; letter-spacing: 0.5px; padding: 8px; border: 1px solid #d1d5db;">UNIT</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @if(count($fields) > 0)
                                                @php
                                                    $rowCount = 0;
                                                @endphp
                                                @foreach($fields as $field)
                                                @php
                                                    $rowCount++;
                                                    $rowClass = ($rowCount % 2 == 0) ? 'background: white;' : 'background: #fef3c7;';
                                                @endphp
                                                @php
                                                    $result = $testResults[$field['name']] ?? null;
                                                    $min = $field['reference_min'] ?? null;
                                                    $max = $field['reference_max'] ?? null;
                                                    $flag = '';
                                                    $flagClass = '';

                                                    if ($result !== null && $result !== '' && $min !== null && $max !== null && is_numeric($result)) {
                                                        $resultValue = floatval($result);
                                                        $minValue = floatval($min);
                                                        $maxValue = floatval($max);

                                                        if ($resultValue < $minValue) {
                                                            $flag = 'LOW';
                                                            $flagClass = 'flag-low';
                                                        } elseif ($resultValue > $maxValue) {
                                                            $flag = 'HIGH';
                                                            $flagClass = 'flag-high';
                                                        } else {
                                                            $flag = 'NORMAL';
                                                            $flagClass = 'flag-normal';
                                                        }
                                                    }
                                                @endphp
                                                <tr style="{{ $rowClass }}">
                                                    <td style="text-align: left; font-weight: 600; color: #374151; font-size: 12px; text-transform: uppercase; padding: 8px; border: 1px solid #e5e7eb;">{{ strtoupper($specimenName) }}</td>
                                                    <td style="text-align: left; font-weight: 500; color: #374151; font-size: 12px; padding: 8px; border: 1px solid #e5e7eb;">{{ strtoupper($result ?? 'N/A') }}</td>
                                                    <td style="text-align: left; font-weight: 500; color: #374151; font-size: 12px; padding: 8px; border: 1px solid #e5e7eb;">{{ $min ?? 'N/A' }} - {{ $max ?? 'N/A' }}</td>
                                                    <td style="text-align: center; padding: 8px; border: 1px solid #e5e7eb;">
                                            @if($flag)
                                                <span class="pathology-flag {{ $flagClass }}">{{ $flag }}</span>
                            @else
                                                            <span style="color: #6b7280; font-size: 10px;">N/A</span>
                            @endif
                        </td>
                                                    <td style="text-align: left; font-weight: 500; color: #374151; font-size: 12px; text-transform: uppercase; padding: 8px; border: 1px solid #e5e7eb;">{{ strtoupper($field['unit'] ?? 'N/A') }}</td>
                                                </tr>
                                                @endforeach
                                            @else
                                                <tr>
                                                    <td colspan="5" style="text-align: center; color: #6b7280; font-style: italic;">No test fields configured</td>
                                                </tr>
                                    @endif
                                        </tbody>
                                    </table>
                                </div>

                            @elseif($tableType === 'field_value_multi')
                                <!-- Field-Value Multi-Column Template Display -->
                                @php
                                    $fieldValueConfig = $formConfig['field_value_config'] ?? [];
                                    $columnsPerRow = $fieldValueConfig['columns'] ?? 4;
                                    $separator = $fieldValueConfig['separator'] ?? ': ';
                                    $fields = $formConfig['fields'] ?? [];
                                    $specimenName = $formConfig['specimen_name'] ?? $template->test_type ?? 'SPECIMEN';
                                @endphp

                                <div class="table-responsive">
                                    <table class="table table-bordered table-sm" style="font-size: 12px; border-collapse: collapse;">
                                        <thead>
                                            <tr style="background-color: #fef3c7;">
                                                <th colspan="{{ $columnsPerRow }}" class="text-center" style="font-weight: bold; color: #92400e; font-size: 12px; padding: 8px 12px; border: 1px solid #ddd;">
                                                    SPECIMEN: {{ strtoupper($template->test_type ) }}
                                                </th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @if(count($fields) > 0)
                                                @php
                                                    $fieldChunks = array_chunk($fields, $columnsPerRow);
                                                @endphp
                                                @foreach($fieldChunks as $rowIndex => $row)
                                                <tr style="background-color: {{ $rowIndex % 2 == 0 ? '#fef3c7' : 'white' }};">
                                                    @foreach($row as $fieldIndex => $field)
                                                    @php
                                                        $result = $testResults[$field['name']] ?? null;
                                                    @endphp
                                                    <td style="padding: 8px 12px; border: 1px solid #ddd; vertical-align: top; background-color: {{ $rowIndex % 2 == 0 ? '#fef3c7' : 'white' }}; text-align: left;">
                                                        <div class="field-value-pair">
                                                            <span style="font-weight: bold; color: #dc2626;">
                                                                {{ strtoupper($field['label'] ?? 'PARAMETER') }}{{ $separator }}
                                                            </span>
                                                            <span style="color: #000000; font-weight: normal;">
                                                                @if($result !== null && $result !== '')
                                                                    {{ strtoupper($result) }}
                                                                @else
                                                                    <span style="color: #7f8c8d;">_________________</span>
                                                                @endif
                                                            </span>
                                                            @if(isset($field['unit']) && $field['unit'])
                                                                <div style="color: #7f8c8d; font-size: 10px; margin-top: 2px;">
                                                                    Unit: {{ $field['unit'] }}
                                                                </div>
                                                            @endif
                                                        </div>
                                                    </td>
                                                    @endforeach
                                                    @for($i = count($row); $i < $columnsPerRow; $i++)
                                                    <td style="padding: 8px 12px; border: 1px solid #ddd; vertical-align: top; background-color: {{ $rowIndex % 2 == 0 ? '#fef3c7' : 'white' }};">
                                                        <!-- Empty column - left blank for clean report appearance -->
                        </td>
                                                    @endfor
                    </tr>
                @endforeach
                                            @else
                                                <tr style="background-color: white;">
                                                    <td colspan="{{ $columnsPerRow }}" class="text-center" style="padding: 20px; color: #7f8c8d; border: 1px solid #ddd;">
                                                        <!-- No fields configured - left blank for clean report appearance -->
                                                    </td>
                                                </tr>
                                            @endif
            </tbody>
        </table>
                                </div>

                            @else
                                <div style="text-align: center; color: #6b7280; font-style: italic; padding: 20px;">
                                    Unknown template type: {{ $tableType }}
                                </div>
                            @endif
                    @else
                            <div style="text-align: center; color: #6b7280; font-style: italic; padding: 20px;">
                                No form configuration found for {{ $template->test_name ?? 'this test' }}. Please configure the template with test fields.
    </div>
    @endif
                </div>
            @endforeach
        @else
                <div style="text-align: center; color: #6b7280; font-style: italic; padding: 20px;">
                    <i class="fas fa-info-circle me-2"></i>
                    No test items found for this request.
    </div>
    @endif

        <!-- Signature Section -->
        <div class="signature-section">
                <div class="row">
                    <div class="col-6">
                        <div class="text-center">
                    <div class="signature-line"></div>
                            <div class="signature-label">(Medical Laboratory Technician)</div>
                        </div>
                </div>
                    <div class="col-6">
                        <div class="text-center">
                    <div class="signature-line"></div>
                            <div class="signature-label">(Authorized Signature)</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
