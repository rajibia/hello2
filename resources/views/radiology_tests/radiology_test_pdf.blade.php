<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "//www.w3.org/TR/html4/strict.dtd">
<html lang="en">

<head>
    <meta http-equiv="Content-Type" content="text/html;charset=UTF-8">
    <title>Radiology Test Report - {{ $radiologyTest->bill_no }}</title>
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

        .radiology-report-container {
            max-width: 800px;
            margin: 0 auto;
            background: white;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }

        .radiology-report-header {
            text-align: center;
            margin-bottom: 0;
            padding: 25px 20px;
            border-bottom: 2px solid #1e40af;
            background: white;
        }

        .radiology-report-title {
            color: #1e40af;
            font-weight: 700;
            font-size: 20px;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 10px;
        }

        .radiology-report-subtitle {
            color: #1e40af;
            font-weight: 700;
            font-size: 18px;
            text-transform: uppercase;
            letter-spacing: 1.5px;
        }

        .radiology-admin-details {
            margin-bottom: 0;
            border: none;
            border-radius: 0;
            overflow: hidden;
        }

        .admin-row {
            page-break-inside: avoid;
        }

        .admin-row-yellow {
            background: #fbbf24;
            padding: 15px 20px;
            margin: 0;
        }

        .admin-row-white {
            background: white;
            padding: 15px 20px;
            margin: 0;
            border-top: 1px solid #e5e7eb;
        }

        .admin-field {
            margin-bottom: 8px;
        }

        .admin-field:last-child {
            margin-bottom: 0;
        }

        .admin-field-indented {
            margin-left: 20px;
        }

        .admin-label {
            font-weight: 700;
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

        .admin-value {
            font-weight: 400;
            color: #000;
        }

        .radiology-results-section {
            background: white;
            border: 1px solid #d1d5db;
            border-radius: 4px;
            margin: 20px;
            overflow: hidden;
            page-break-inside: avoid;
        }

        .radiology-results-header {
            background: #fbbf24;
            padding: 15px 20px;
        }

        .radiology-results-header h6 {
            color: #92400e;
            font-weight: 700;
            font-size: 14px;
            text-transform: uppercase;
            letter-spacing: 0.8px;
            margin: 0;
        }

        .radiology-results-table {
            width: 100%;
            border-collapse: collapse;
            background: white;
        }

        .radiology-results-table thead {
            background: #f3f4f6;
        }

        .radiology-results-table th {
            color: #374151;
            font-weight: 600;
            font-size: 11px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            padding: 12px 8px;
            text-align: center;
            border: 1px solid #d1d5db;
            background: #f3f4f6;
        }

        .radiology-results-table tbody tr {
            border-bottom: 1px solid #e5e7eb;
        }

        .radiology-results-table tbody tr:nth-child(even) {
            background: #f8f9fa;
        }

        .radiology-results-table td {
            padding: 12px 8px;
            font-size: 12px;
            border: 1px solid #e5e7eb;
            vertical-align: middle;
        }

        .radiology-results-table td:first-child {
            background: #f8f9fa;
            font-weight: 600;
            color: #374151;
            text-align: left;
        }

        .radiology-flag {
            padding: 4px 8px;
            border-radius: 3px;
            font-size: 10px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.3px;
            display: inline-block;
            min-width: 50px;
            text-align: center;
        }

        .flag-low {
            background: #fef3c7;
            color: #92400e;
            border: 1px solid #fcd34d;
        }

        .flag-high {
            background: #fee2e2;
            color: #991b1b;
            border: 1px solid #f87171;
        }

        .flag-normal {
            background: #d1fae5;
            color: #065f46;
            border: 1px solid #6ee7b7;
        }

        .signature-section {
            margin: 20px;
            padding: 25px 0;
            border-top: 2px solid #1e40af;
            page-break-inside: avoid;
        }

        .signature-container {
            display: flex;
            justify-content: space-between;
            align-items: flex-end;
            width: 100%;
        }

        .signature-item {
            text-align: center;
            width: 200px;
        }

        .signature-line {
            border-top: 2px solid #374151;
            width: 180px;
            margin-bottom: 8px;
        }

        .signature-label {
            font-size: 11px;
            color: #6b7280;
            text-transform: uppercase;
            letter-spacing: 0.8px;
            font-weight: 600;
        }

        @page {
            margin: 20px;
        }
    </style>
</head>

<body>
    <div class="radiology-report-container">
        <!-- Radiology Report Header -->
        <div class="radiology-report-header">
            <h4 class="radiology-report-title mb-0">
                CARDINAL NAMDINI MINING LTD, CLINIC RADIOLOGY
            </h4>
            <h5 class="radiology-report-subtitle mb-0">
                RADIOLOGY RESULTS
            </h5>
        </div>

        <!-- Administrative Details Section -->
        <div class="radiology-admin-details">
            <!-- First Row - Yellow Background -->
            <div class="admin-row admin-row-yellow">
                <div style="display: flex; justify-content: space-between; align-items: center;">
                    <span class="admin-label admin-label-yellow">DATE : {{ $radiologyTest->created_at ? $radiologyTest->created_at->format('d/m/Y') : 'N/A' }}</span>
                    <span class="admin-label admin-label-yellow">SPECIMEN : RADIOLOGY</span>
                    <span class="admin-label admin-label-yellow">LAB NO : {{ $radiologyTest->bill_no ?? 'N/A' }}</span>
                </div>
            </div>

            <!-- Second Row - White Background -->
            <div class="admin-row admin-row-white">
                <div style="display: flex; justify-content: space-between; align-items: center;">
                    <span>
                        <span class="admin-label admin-label-white">NAME OF PATIENT : </span>
                        <span class="admin-value">{{ strtoupper($radiologyTest->patient->patientUser->full_name ?? 'N/A') }}</span>
                    </span>
                    <span>
                        <span class="admin-label admin-label-white">AGE : </span>
                        <span class="admin-value">{{ \Carbon\Carbon::parse($radiologyTest->patient->patientUser->dob ?? now())->age ?? 'N/A' }} YRS</span>
                        <span class="admin-label admin-label-white" style="margin-left: 20px;">SEX : </span>
                        <span class="admin-value">{{ $radiologyTest->patient->patientUser->gender ? 'F' : 'M' }}</span>
                    </span>
                </div>
            </div>

            <!-- Third Row - White Background -->
            <div class="admin-row admin-row-white">
                <div style="display: flex; justify-content: space-between; align-items: center;">
                    <span>
                        <span class="admin-label admin-label-white">DIAGNOSIS : </span>
                        <span class="admin-value">{{ strtoupper($radiologyTest->diagnosis ?? 'N/A') }}</span>
                    </span>
                    <span>
                        <span class="admin-label admin-label-white">TEST REQUESTED : </span>
                        <span class="admin-value">
                            @if($radiologyTest->radiologyTestItems && $radiologyTest->radiologyTestItems->count() > 0)
                                @php
                                    $testNames = $radiologyTest->radiologyTestItems->pluck('radiologytesttemplate.test_name')->filter()->toArray();
                                @endphp
                                {{ strtoupper(implode(', ', $testNames)) }}
                            @else
                                NO TESTS
                            @endif
                        </span>
                    </span>
                </div>
            </div>

            <!-- Fourth Row - White Background -->
            <div class="admin-row admin-row-white">
                <div style="display: flex; justify-content: space-between; align-items: center;">
                    <span>
                        <span class="admin-label admin-label-white">NAME OF CLINICIAN : </span>
                        <span class="admin-value">{{ strtoupper($radiologyTest->doctor->doctorUser->full_name ?? 'N/A') }}</span>
                    </span>
                    <span>
                        <span class="admin-label admin-label-white">TEST PERFORMED BY : </span>
                        <span class="admin-value">{{ strtoupper($radiologyTest->performed_by_user->full_name ?? $radiologyTest->performed_by_user->name ?? 'N/A') }}</span>
                    </span>
                </div>
            </div>
                        </div>

        <!-- Test Results Sections for Each Test Item -->
        @if($radiologyTest->radiologyTestItems && $radiologyTest->radiologyTestItems->count() > 0)
            @foreach($radiologyTest->radiologyTestItems as $index => $testItem)
                @php
                    $template = $testItem->radiologytesttemplate;
                    $formConfig = $template ? ($template->form_configuration ?? []) : [];
                    $testResults = $radiologyTest->test_results[$testItem->id] ?? [];
                @endphp

                <div class="radiology-results-section">
                    <div class="radiology-results-header">
                        <h6 class="mb-0">
                            {{ strtoupper($template->test_name ?? 'TEST') }}
                        </h6>
    </div>

                    @if(!empty($formConfig))
                        @php
                            // Check if any field has reference range data
                            $hasReferenceRange = false;
                            $hasUnit = false;
                            foreach($formConfig as $field) {
                                if (!empty($field['reference_min']) || !empty($field['reference_max'])) {
                                    $hasReferenceRange = true;
                                }
                                if (!empty($field['unit'])) {
                                    $hasUnit = true;
                                }
                            }
                        @endphp
                        <table class="radiology-results-table">
                            <thead>
                                <tr>
                                    <th style="width: {{ $hasReferenceRange ? '25%' : '50%' }};">ANALYTE</th>
                                    <th style="width: {{ $hasReferenceRange ? '25%' : '50%' }};">RESULTS</th>
                                    @if($hasReferenceRange)
                                        <th style="width: 25%;">REFERENCE RANGE</th>
                                        <th style="width: 12%;">FLAG</th>
                                    @endif
                                    @if($hasUnit)
                                        <th style="width: 13%;">UNIT</th>
                                    @endif
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($formConfig as $field)
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
                                <tr>
                                    <td style="font-weight: 600; text-align: left; background: #f8f9fa;">{{ strtoupper($field['label']) }}</td>
                                    <td style="text-align: center; font-weight: 400; color: #000;">
                                        @if($result !== null && $result !== '')
                                            {{ strtoupper($result) }}
                                        @else
                                            <span style="color: #9ca3af;">-</span>
                                        @endif
                                    </td>
                                    @if($hasReferenceRange)
                                        <td style="text-align: center; font-size: 11px; color: #6b7280;">
                                            @if(!empty($field['reference_min']) || !empty($field['reference_max']))
                                                {{ $field['reference_min'] ?? 'N/A' }} - {{ $field['reference_max'] ?? 'N/A' }}
                                            @else
                                                N/A
                                            @endif
                                    </td>
                                        <td style="text-align: center;">
                                            @if($flag)
                                                <span class="radiology-flag {{ $flagClass }}">{{ $flag }}</span>
                                            @else
                                                <span style="color: #9ca3af;">-</span>
                                            @endif
                                    </td>
                                    @endif
                                    @if($hasUnit)
                                        <td style="text-align: center; font-size: 11px; color: #6b7280;">
                                            {{ strtoupper($field['unit'] ?? 'N/A') }}
                                    </td>
                                    @endif
                                </tr>
                                @endforeach
                            </tbody>
                            </table>
                    @else
                        <div style="margin: 20px; background: #eff6ff; border: 1px solid #93c5fd; color: #1e40af; padding: 12px 16px; border-radius: 4px;">
                            <strong>No test configuration available for this test.</strong>
                        </div>
                    @endif
    </div>
            @endforeach
                @else
            <div style="margin: 20px; background: #fffbeb; border: 1px solid #fbbf24; color: #92400e; padding: 12px 16px; border-radius: 4px;">
                <strong>No test items found for this radiology test.</strong>
            </div>
                @endif

        <!-- Signature Section -->
        <div class="signature-section">
            <div class="signature-container">
                <div class="signature-item">
                    <div class="signature-line"></div>
                    <div class="signature-label">RADIOLOGY TECHNICIAN</div>
                </div>
                <div class="signature-item">
                    <div class="signature-line"></div>
                    <div class="signature-label">RADIOLOGIST</div>
                </div>
            </div>
    </div>
    </div>
</body>
</html>
