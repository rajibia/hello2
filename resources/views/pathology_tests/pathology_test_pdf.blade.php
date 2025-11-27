<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Laboratory Test Report - {{ $pathologyTest->lab_number ?? $pathologyTest->bill_no }}</title>
    <style>
        @page { margin: 40px 50px; }
        body {
            font-family: DejaVu Sans, Arial, sans-serif;
            font-size: 12px;
            line-height: 1.5;
            color: #222;
            background: #fff;
        }
        .container { max-width: 800px; margin: 0 auto; background: white; padding: 20px; }
        .header { text-align: center; padding: 20px 0; border-bottom: 3px solid #f59e0b; margin-bottom: 25px; }
        .logo {
            width: 70px; height: 70px; border: 2px solid #e5e7eb; border-radius: 12px; overflow: hidden; display: inline-block;
        }
        .company { font-size: 22px; font-weight: 900; color: #1e3a8a; text-transform: uppercase; margin: 10px 0 5px; }
        .subtitle { font-size: 16px; color: #1e40af; font-weight: 600; text-transform: uppercase; }
        .info-row {
            display: flex; flex-wrap: wrap; background: #fbbf24; padding: 12px 16px; border-radius: 8px; margin-bottom: 10px;
            font-weight: 600; color: #1f2937; text-transform: uppercase; font-size: 11.5px;
        }
        .info-row-white {
            background: #fff; border: 1px solid #e5e7eb; padding: 12px 16px; border-radius: 8px; margin-bottom: 10px;
            display: flex; justify-content: space-between; flex-wrap: wrap;
        }
        .col-3 { flex: 0 0 33.33%; }
        .col-2 { flex: 0 0 50%; }
        .text-right { text-align: right; }
        .text-center { text-align: center; }
        .test-section { margin: 30px 0; page-break-inside: avoid; }
        .test-header {
            background: #ff6b35; color: white; padding: 12px 20px; border-radius: 8px 8px 0 0 0;
            font-size: 14px; font-weight: 700; text-transform: uppercase;
        }
        table { width: 100%; border-collapse: collapse; margin-top: 0; }
        th, td { border: 1px solid #d1d5db; padding: 10px; text-align: left; font-size: 11.5px; }
        th { background: #fef3c7; color: #92400e; font-weight: 700; text-transform: uppercase; }
        tr:nth-child(even) td { background: #fffbeb; }
        .flag { padding: 4px 10px; border-radius: 4px; font-size: 10px; font-weight: bold; color: white; }
        .flag-low { background: #f59e0b; }
        .flag-high { background: #dc2626; }
        .flag-normal { background: #16a34a; }
        .signature {
            margin-top: 60px; display: flex; justify-content: space-between; page-break-inside: avoid;
        }
        .sig-box { width: 45%; text-align: center; }
        .line { border-top: 2px solid #333; width: 220px; margin: 50px auto 10px; }
        .sig-label { font-size: 11px; color: #4b5563; font-weight: 600; text-transform: uppercase; }
        @media print {
            body { -webkit-print-color-adjust: exact; print-color-adjust: exact; }
            .no-print { display: none; }
        }
    </style>
</head>
<body>

<div class="container">

    <!-- Header -->
    <div class="header">
        <div style="display:flex; justify-content:center; align-items:center; gap:25px;">
            <div class="logo">
                @php
                    $logoUrl = getLogoUrl();
                    $companyName = getCompanyName();
                @endphp
                @if($logoUrl && !str_contains($logoUrl, 'default_image.jpg'))
                    <img src="{{ $logoUrl }}" alt="Logo" style="width:100%; height:100%; object-fit:contain;">
                @else
                    <div style="display:flex; flex-direction:column; justify-content:center; height:100%; color:#6b7280; font-size:10px;">
                        <div>{{ substr($companyName ?? 'CLINIC', 0, 8) }}</div>
                        <div>LAB</div>
                    </div>
                @endif
            </div>
            <div>
                <div class="company">{{ getCompanyName() ?? 'CARDINAL NAMDINI MINING LTD' }}</div>
                <div class="subtitle">Clinic Laboratory - Hospital Management System</div>
            </div>
        </div>
        <h2 style="margin-top:20px; color:#1e40af; font-size:24px;">LABORATORY TEST REPORT</h2>
    </div>

    <!-- Patient Info Rows -->
    <div class="info-row">
        <div class="col-3">DATE: {{ $pathologyTest->created_at?->format('d/m/Y') ?? 'N/A' }}</div>
        <div class="col-3 text-center">SPECIMEN: {{ strtoupper($pathologyTest->pathologyTestItems->first()?->pathologytesttemplate?->test_type ?? 'BLOOD') }}</div>
        <div class="col-3 text-right">LAB NO: {{ $pathologyTest->lab_number ?? 'N/A' }}</div>
    </div>

    <div class="info-row-white">
        <div class="col-2">
            PATIENT: {{ strtoupper($pathologyTest->patient?->patientUser?->full_name ?? 'N/A') }}
        </div>
        <div class="col-2 text-right">
            AGE: 
            @php
                $dob = $pathologyTest->patient?->patientUser?->dob;
                $age = $dob ? \Carbon\Carbon::parse($dob)->age : null;
            @endphp
            {{ $age ? $age . ' YRS' : 'N/A' }}
            &nbsp;&nbsp;|&nbsp;&nbsp;
            SEX: {{ $pathologyTest->patient?->patientUser?->gender ? 'FEMALE' : 'MALE' }}
        </div>
    </div>

    <div class="info-row-white">
        <div class="col-2">
            DIAGNOSIS: {{ strtoupper($pathologyTest->diagnosis ?? 'NOT SPECIFIED') }}
        </div>
        <div class="col-2 text-right">
            REQUESTED BY: {{ strtoupper($pathologyTest->doctor?->doctorUser?->full_name ?? 'N/A') }}
        </div>
    </div>

    <div class="info-row-white">
        <div class="col-2">
            TEST(S): 
            @if($pathologyTest->pathologyTestItems->count() > 0)
                {{ $pathologyTest->pathologyTestItems->pluck('pathologytesttemplate.test_name')->implode(', ') }}
            @else
                NONE
            @endif
        </div>
        <div class="col-2 text-right">
            PERFORMED BY: {{ strtoupper($pathologyTest->performed_by_user?->full_name ?? $pathologyTest->labTechnician?->name ?? 'N/A') }}
        </div>
    </div>

    <hr style="margin:30px 0; border:1px dashed #e5e7eb;">

    <!-- Test Results -->
    @if($pathologyTest->pathologyTestItems->count() > 0)
        @foreach($pathologyTest->pathologyTestItems as $item)
            @php
                $template = $item->pathologytesttemplate;
                $config = $template?->form_configuration ? (is_string($template->form_configuration) ? json_decode($template->form_configuration, true) : $template->form_configuration) : [];
                $results = $pathologyTest->test_results[$item->id] ?? [];
                $tableType = $config['table_type'] ?? 'standard';
            @endphp

            <div class="test-section">
                <div class="test-header">
                    {{ strtoupper($template?->test_name ?? 'TEST RESULT') }}
                </div>

                @if($tableType === 'standard' && isset($config['fields']))
                    <table>
                        <thead>
                            <tr>
                                <th>PARAMETER</th>
                                <th>RESULT</th>
                                <th>REFERENCE RANGE</th>
                                <th>UNIT</th>
                                <th>FLAG</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($config['fields as $field)
                                @php
                                    $value = $results[$field['name']] ?? null;
                                    $flag = '';
                                    if ($value !== null && is_numeric($value) && isset($field['reference_min'], $field['reference_max'])) {
                                        $v = (float)$value;
                                        $min = (float)$field['reference_min'];
                                        $max = (float)$field['reference_max'];
                                        if ($v < $min) $flag = 'LOW';
                                        elseif ($v > $max) $flag = 'HIGH';
                                        else $flag = 'NORMAL';
                                    }
                                @endphp
                                <tr>
                                    <td><strong>{{ strtoupper($field['label'] ?? $field['name']) }}</strong></td>
                                    <td><strong>{{ $value ?? '-' }}</strong></td>
                                    <td>{{ $field['reference_min'] ?? '?' }} - {{ $field['reference_max'] ?? '?' }}</td>
                                    <td>{{ strtoupper($field['unit'] ?? '') }}</td>
                                    <td>
                                        @if($flag)
                                            <span class="flag flag-{{ strtolower($flag) }}">{{ $flag }}</span>
                                        @else
                                            <span style="color:#9ca3af;">—</span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                @elseif($tableType === 'field_value_multi')
                    <div style="padding:20px; background:#fefce8; border:1px solid #fcd34d; border-radius:8px;">
                        @foreach($config['fields'] ?? [] as $field)
                            <div style="margin:8px 0; font-size:13px;">
                                <strong>{{ strtoupper($field['label'] ?? $field['name']) }}:</strong>
                                {{ $results[$field['name']] ?? '_________________' }}
                                @if(!empty($field['unit'])) <small>({{ $field['unit'] }})</small> @endif
                            </div>
                        @endforeach
                    </div>

                @else
                    <p style="padding:20px; text-align:center; color:#666; font-style:italic;">
                        Result format not fully supported yet — contact admin.
                    </p>
                @endif
            </div>
        @endforeach
    @else
        <p style="text-align:center; padding:50px; color:#999; font-size:16px;">
            No test results available for this report.
        </p>
    @endif

    <!-- Signatures -->
    <div class="signature">
        <div class="sig-box">
            <div class="line"></div>
            <div class="sig-label">Medical Laboratory Scientist / Technician</div>
        </div>
        <div class="sig-box">
            <div class="line"></div>
            <div class="sig-label">Pathologist / Medical Officer</div>
        </div>
    </div>

</div>
</body>
</html>