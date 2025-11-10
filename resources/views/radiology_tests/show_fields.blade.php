<!-- Include Radiology Report CSS -->
<link href="{{ asset('assets/css/radiology-report.css') }}" rel="stylesheet">

<div class="radiology-report-container" id="radiology-report-content">
    <!-- Radiology Report Header -->
    <div class="radiology-report-header">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <div class="d-flex align-items-center">
                <!-- Logo placeholder -->
                <div class="me-3">
                    <div style="width: 60px; height: 60px; background: linear-gradient(45deg, #3b82f6, #1d4ed8); border-radius: 8px; display: flex; align-items: center; justify-content: center; color: white; font-weight: bold; font-size: 10px; text-align: center;">
<div>
                            <div style="font-size: 8px;">SD-GOLD</div>
                            <div style="font-size: 6px;">CARDINAL</div>
                        </div>
                    </div>
                </div>
                <div>
                    <h4 class="radiology-report-title mb-0" style="font-size: 18px; font-weight: 700; color: #1f2937; text-transform: uppercase; letter-spacing: 0.5px;">
                        CARDINAL NAMDINI MINING LTD, CLINIC RADIOLOGY
                    </h4>
                </div>
            </div>
            <!-- Print Button -->
            <div class="no-print">
                <button onclick="printRadiologyReport()" class="btn btn-success me-2">
                    <i class="fas fa-print"></i> Print Report
                </button>
                <a href="{{ route('radiology.test.pdf', $radiologyTest->id) }}" target="_blank" class="btn btn-outline-success">
                    <i class="fas fa-file-pdf"></i> PDF
                </a>
            </div>
        </div>
        <div class="text-center">
            <h5 class="radiology-report-subtitle mb-0" style="font-size: 16px; font-weight: 600; color: #1e40af; text-transform: uppercase; letter-spacing: 1px;">
                RADIOLOGY RESULTS
            </h5>
        </div>
    </div>

    <div class="radiology-report-body">
        <!-- Administrative Details Section -->
        <div class="radiology-admin-details">
            <!-- First Row - Yellow Background -->
            <div class="row mb-2" style="background: #fbbf24; padding: 8px 12px; border-radius: 4px; margin: 0;">
                <div class="col-4">
                    <span class="radiology-admin-label" style="font-weight: 600; color: #92400e; font-size: 12px; text-transform: uppercase;">DATE : {{ $radiologyTest->created_at ? $radiologyTest->created_at->format('d/m/Y') : 'N/A' }}</span>
                </div>
                <div class="col-4 text-center">
                    <span class="radiology-admin-label" style="font-weight: 600; color: #92400e; font-size: 12px; text-transform: uppercase;">SPECIMEN : RADIOLOGY</span>
                            </div>
                <div class="col-4 text-end">
                    <span class="radiology-admin-label" style="font-weight: 600; color: #92400e; font-size: 12px; text-transform: uppercase;">LAB NO : {{ $radiologyTest->bill_no ?? 'N/A' }}</span>
                            </div>
                            </div>

            <!-- Second Row - White Background -->
            <div class="row mb-2" style="background: white; padding: 8px 12px; border-radius: 4px; margin: 0; border: 1px solid #e5e7eb;">
                <div class="col-6">
                    <span class="radiology-admin-label" style="font-weight: 600; color: #374151; font-size: 12px; text-transform: uppercase;">NAME OF PATIENT : {{ strtoupper($radiologyTest->patient->patientUser->full_name ?? 'N/A') }}</span>
                            </div>
                <div class="col-6 text-end">
                    <span class="radiology-admin-label" style="font-weight: 600; color: #374151; font-size: 12px; text-transform: uppercase;">AGE : {{ \Carbon\Carbon::parse($radiologyTest->patient->patientUser->dob ?? now())->age ?? 'N/A' }} YRS</span>
                    <span class="radiology-admin-label ms-3" style="font-weight: 600; color: #374151; font-size: 12px; text-transform: uppercase;">SEX : {{ $radiologyTest->patient->patientUser->gender ? 'F' : 'M' }}</span>
                            </div>
                            </div>

            <!-- Third Row - White Background -->
            <div class="row mb-2" style="background: white; padding: 8px 12px; border-radius: 4px; margin: 0; border: 1px solid #e5e7eb;">
                <div class="col-6">
                    <span class="radiology-admin-label" style="font-weight: 600; color: #374151; font-size: 12px; text-transform: uppercase;">DIAGNOSIS : {{ strtoupper($radiologyTest->diagnosis ?? 'N/A') }}</span>
                            </div>
                <div class="col-6 text-end">
                    <span class="radiology-admin-label" style="font-weight: 600; color: #374151; font-size: 12px; text-transform: uppercase;">TEST REQUESTED :
                        @if($radiologyTest->radiologyTestItems && $radiologyTest->radiologyTestItems->count() > 0)
                            @php
                                $testNames = $radiologyTest->radiologyTestItems->pluck('radiologytesttemplate.test_name')->filter()->toArray();
                            @endphp
                            {{ strtoupper(implode(', ', $testNames)) }}
                        @else
                            NO TESTS
                        @endif
                    </span>
                            </div>
                            </div>

            <!-- Fourth Row - White Background -->
            <div class="row mb-3" style="background: white; padding: 8px 12px; border-radius: 4px; margin: 0; border: 1px solid #e5e7eb;">
                <div class="col-6">
                    <span class="radiology-admin-label" style="font-weight: 600; color: #374151; font-size: 12px; text-transform: uppercase;">NAME OF CLINICIAN : {{ strtoupper($radiologyTest->doctor->doctorUser->full_name ?? 'N/A') }}</span>
                            </div>
                <div class="col-6 text-end">
                    <span class="radiology-admin-label" style="font-weight: 600; color: #374151; font-size: 12px; text-transform: uppercase;">TEST PERFORMED BY : {{ strtoupper($radiologyTest->performed_by_user->full_name ?? $radiologyTest->performed_by_user->name ?? 'N/A') }}</span>
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
                    <div class="radiology-results-header" style="background: #fbbf24 !important; padding: 12px 20px !important; border-bottom: 2px solid #f59e0b !important;">
                        <h6 class="mb-0" style="color: #92400e !important; font-weight: 700 !important; font-size: 14px !important; text-transform: uppercase !important; letter-spacing: 0.5px !important; margin: 0 !important;">
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

                            // Determine table class based on columns
                            $tableClass = 'radiology-results-table';
                            if (!$hasReferenceRange && !$hasUnit) {
                                $tableClass .= ' two-columns';
                            } elseif ($hasReferenceRange && !$hasUnit) {
                                $tableClass .= ' three-columns';
                            }
                        @endphp
                        <div class="table-responsive">
                            <table class="{{ $tableClass }}">
                                <thead>
                                    <tr>
                                        <th style="width: {{ $hasReferenceRange ? '30%' : '50%' }};">ANALYTE</th>
                                        <th style="width: {{ $hasReferenceRange ? '30%' : '50%' }};">RESULTS</th>
                                        @if($hasReferenceRange)
                                            <th style="width: 25%;">REFERENCE RANGE</th>
                                            <th style="width: 15%;">FLAG</th>
                                        @endif
                                        @if($hasUnit)
                                            <th style="width: 15%;">UNIT</th>
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
                                        <td style="text-align: center; font-weight: 500; color: #374151;">
                                            @if($result !== null && $result !== '')
                                                {{ strtoupper($result) }}
                                            @else
                                                <span style="color: #9ca3af;">-</span>
                                            @endif
                                        </td>
                                        @if($hasReferenceRange)
                                            <td style="text-align: center; font-size: 12px; color: #6b7280;">
                                                @if(!empty($field['reference_min']) || !empty($field['reference_max']))
                                                    {{ $field['reference_min'] ?? 'N/A' }} - {{ $field['reference_max'] ?? 'N/A' }}
                                                @else
                                                    N/A
                                                @endif
                                            </td>
                                            <td style="text-align: center;">
                                                @if($flag)
                                                    <span class="radiology-flag {{ $flagClass }}" style="
                                                        padding: 2px 8px;
                                                        border-radius: 3px;
                                                        font-size: 10px;
                                                        font-weight: 600;
                                                        text-transform: uppercase;
                                                        {{ $flagClass == 'flag-low' ? 'background: #fef3c7; color: #92400e;' : '' }}
                                                        {{ $flagClass == 'flag-high' ? 'background: #fee2e2; color: #991b1b;' : '' }}
                                                        {{ $flagClass == 'flag-normal' ? 'background: #d1fae5; color: #065f46;' : '' }}
                                                    ">{{ $flag }}</span>
                                    @else
                                                    <span style="color: #9ca3af;">-</span>
                                                @endif
                                                </td>
                                        @endif
                                        @if($hasUnit)
                                            <td style="text-align: center; font-size: 12px; color: #6b7280;">
                                                {{ strtoupper($field['unit'] ?? 'N/A') }}
                                                </td>
                                        @endif
                                            </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="alert alert-info" style="margin: 20px; background: #eff6ff; border: 1px solid #93c5fd; color: #1e40af; padding: 12px 16px; border-radius: 4px;">
                            <i class="fas fa-info-circle me-2"></i>
                            No test configuration available for this test.
                        </div>
                    @endif
                </div>
            @endforeach
        @else
            <div class="alert alert-warning" style="margin: 20px; background: #fffbeb; border: 1px solid #fbbf24; color: #92400e; padding: 12px 16px; border-radius: 4px;">
                <i class="fas fa-exclamation-triangle me-2"></i>
                No test items found for this radiology test.
            </div>
        @endif

        <!-- Signature Section -->
        <div class="signature-section" style="margin-top: 30px; padding-top: 20px; border-top: 2px solid #e5e7eb;">
            <div class="row">
                <div class="col-6">
                    <div class="signature-line" style="border-top: 1px solid #374151; width: 200px; margin-top: 40px; margin-bottom: 5px;"></div>
                    <div class="signature-label" style="font-size: 11px; color: #6b7280; text-transform: uppercase; letter-spacing: 0.5px;">RADIOLOGY TECHNICIAN</div>
                    </div>
                <div class="col-6 text-end">
                    <div class="signature-line" style="border-top: 1px solid #374151; width: 200px; margin-top: 40px; margin-bottom: 5px; margin-left: auto;"></div>
                    <div class="signature-label" style="font-size: 11px; color: #6b7280; text-transform: uppercase; letter-spacing: 0.5px;">RADIOLOGIST</div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    /* Ensure yellow headers are applied correctly */
    .radiology-results-header {
        background: #fbbf24 !important;
        padding: 12px 20px !important;
        border-bottom: 2px solid #f59e0b !important;
    }

    .radiology-results-header h6 {
        color: #92400e !important;
        font-weight: 700 !important;
        font-size: 14px !important;
        text-transform: uppercase !important;
        letter-spacing: 0.5px !important;
        margin: 0 !important;
    }

    /* Override any conflicting styles */
    .radiology-results-section .radiology-results-header {
        background: #fbbf24 !important;
    }

    .radiology-results-section .radiology-results-header h6 {
        color: #92400e !important;
    }

    /* Most specific selectors to override any framework styles */
    div.radiology-results-section div.radiology-results-header {
        background: #fbbf24 !important;
        background-color: #fbbf24 !important;
    }

    div.radiology-results-section div.radiology-results-header h6 {
        color: #92400e !important;
        color: #92400e !important;
    }

    /* Override any table header styles that might be affecting our headers */
    .radiology-results-header,
    .radiology-results-header * {
        background: #fbbf24 !important;
        background-color: #fbbf24 !important;
    }

    .radiology-results-header h6,
    .radiology-results-header h6 * {
        color: #92400e !important;
    }

    /* Print Styles */
    @media print {
        /* Force background colors to print */
        * {
            -webkit-print-color-adjust: exact !important;
            color-adjust: exact !important;
            print-color-adjust: exact !important;
        }

        /* Hide all navigation and unnecessary elements */
        .no-print,
        .header,
        .sidebar,
        .footer,
        .breadcrumb,
        .btn,
        .navbar,
        .modal,
        .dropdown,
        .alert {
            display: none !important;
        }

        /* Reset body and container for print */
        body {
            margin: 0 !important;
            padding: 0 !important;
            background: white !important;
            font-size: 12px !important;
            line-height: 1.4 !important;
        }

        .container-fluid,
        .container {
            width: 100% !important;
            max-width: none !important;
            margin: 0 !important;
            padding: 0 !important;
        }

        /* Radiology report container */
        .radiology-report-container {
            background: white !important;
            margin: 0 !important;
            padding: 20px !important;
            box-shadow: none !important;
            border: none !important;
        }

        /* Header styling for print - EXACT SAME AS MODAL */
        .radiology-report-header {
            background: white !important;
            padding: 20px 25px 15px 25px !important;
            border-bottom: 2px solid #e5e7eb !important;
            margin-bottom: 20px !important;
        }

        .radiology-report-title {
            color: #1f2937 !important;
            font-weight: 700 !important;
            font-size: 18px !important;
            text-transform: uppercase !important;
            letter-spacing: 0.5px !important;
            margin: 0 !important;
        }

        .radiology-report-subtitle {
            color: #1e40af !important;
            font-weight: 600 !important;
            font-size: 16px !important;
            text-transform: uppercase !important;
            letter-spacing: 1px !important;
            margin: 0 !important;
        }

        /* Administrative details for print - EXACT SAME AS MODAL */
        .radiology-admin-details {
            margin-bottom: 25px !important;
        }

        .radiology-admin-details .row {
            margin-bottom: 10px !important;
            page-break-inside: avoid !important;
        }

        /* First row - Yellow background */
        .radiology-admin-details .row:first-child {
            background: #fbbf24 !important;
            background-color: #fbbf24 !important;
            padding: 8px 12px !important;
            border-radius: 4px !important;
            margin: 0 !important;
        }

        /* White background rows */
        .radiology-admin-details .row:not(:first-child) {
            background: white !important;
            background-color: white !important;
            padding: 8px 12px !important;
            border-radius: 4px !important;
            margin: 0 !important;
            border: 1px solid #e5e7eb !important;
        }

        .radiology-admin-label {
            font-weight: 600 !important;
            font-size: 12px !important;
            text-transform: uppercase !important;
            letter-spacing: 0.3px !important;
        }

        /* Yellow row labels */
        .radiology-admin-details .row:first-child .radiology-admin-label {
            color: #92400e !important;
        }

        /* White row labels */
        .radiology-admin-details .row:not(:first-child) .radiology-admin-label {
            color: #374151 !important;
        }

        /* Test results sections for print - EXACT SAME AS MODAL */
        .radiology-results-section {
            background: white !important;
            border-radius: 8px !important;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1) !important;
            margin-bottom: 20px !important;
            overflow: hidden !important;
            page-break-inside: avoid !important;
        }

        .radiology-results-header {
            background: #fbbf24 !important;
            background-color: #fbbf24 !important;
            padding: 12px 20px !important;
            border-bottom: 2px solid #f59e0b !important;
        }

        .radiology-results-header h6 {
            color: #92400e !important;
            font-weight: 700 !important;
            font-size: 14px !important;
            text-transform: uppercase !important;
            letter-spacing: 0.5px !important;
            margin: 0 !important;
        }

        /* Ensure yellow background is preserved in print */
        .radiology-results-header,
        .radiology-results-header * {
            background: #fbbf24 !important;
            background-color: #fbbf24 !important;
        }

        .radiology-results-header h6,
        .radiology-results-header h6 * {
            color: #92400e !important;
        }

        /* Table styling for print - EXACT SAME AS MODAL */
        .radiology-results-table {
            width: 100% !important;
            border-collapse: collapse !important;
            background: white !important;
        }

        .radiology-results-table thead {
            background: #f3f4f6 !important;
        }

        .radiology-results-table th {
            color: #374151 !important;
            font-weight: 600 !important;
            font-size: 11px !important;
            text-transform: uppercase !important;
            letter-spacing: 0.5px !important;
            padding: 12px 8px !important;
            text-align: center !important;
            border: 1px solid #d1d5db !important;
        }

        .radiology-results-table tbody tr {
            border-bottom: 1px solid #e5e7eb !important;
        }

        .radiology-results-table tbody tr:nth-child(even) {
            background: #f8f9fa !important;
        }

        .radiology-results-table td {
            padding: 10px 8px !important;
            font-size: 12px !important;
            border: 1px solid #e5e7eb !important;
            vertical-align: middle !important;
        }

        .radiology-results-table td:first-child {
            background: #f8f9fa !important;
            font-weight: 600 !important;
            color: #374151 !important;
        }

        /* Flag styling for print - EXACT SAME AS MODAL */
        .radiology-flag {
            padding: 3px 8px !important;
            border-radius: 3px !important;
            font-size: 10px !important;
            font-weight: 600 !important;
            text-transform: uppercase !important;
            letter-spacing: 0.3px !important;
            display: inline-block !important;
            min-width: 50px !important;
            text-align: center !important;
        }

        .flag-low {
            background: #fef3c7 !important;
            color: #92400e !important;
            border: 1px solid #fcd34d !important;
        }

        .flag-high {
            background: #fee2e2 !important;
            color: #991b1b !important;
            border: 1px solid #f87171 !important;
        }

        .flag-normal {
            background: #d1fae5 !important;
            color: #065f46 !important;
            border: 1px solid #6ee7b7 !important;
        }

        /* Signature section for print - EXACT SAME AS MODAL */
        .signature-section {
            margin-top: 30px !important;
            padding-top: 20px !important;
            border-top: 2px solid #e5e7eb !important;
            page-break-inside: avoid !important;
        }

        .signature-line {
            border-top: 1px solid #374151 !important;
            width: 200px !important;
            margin-top: 40px !important;
            margin-bottom: 5px !important;
        }

        .signature-label {
            font-size: 11px !important;
            color: #6b7280 !important;
            text-transform: uppercase !important;
            letter-spacing: 0.5px !important;
        }

        /* Hide logo in print */
        .radiology-report-header .me-3 {
            display: none !important;
        }

        /* Ensure proper page breaks */
        .radiology-results-section {
            page-break-inside: avoid !important;
        }

        /* Remove any shadows or effects */
        * {
            box-shadow: none !important;
            text-shadow: none !important;
        }

        /* Ensure specific colors are preserved in print */
        .radiology-admin-details .row:first-child,
        .radiology-admin-details .row:first-child * {
            background: #fbbf24 !important;
            background-color: #fbbf24 !important;
        }

        .radiology-admin-details .row:first-child .radiology-admin-label {
            color: #92400e !important;
        }

        .radiology-results-header,
        .radiology-results-header * {
            background: #fbbf24 !important;
            background-color: #fbbf24 !important;
        }

        .radiology-results-header h6 {
            color: #92400e !important;
        }
    }
</style>

<script>
    function printRadiologyReport() {
        // Store current page title
        const originalTitle = document.title;

        // Set page title for print
        document.title = 'Radiology Test Report - {{ $radiologyTest->bill_no }}';

        // Hide all unnecessary elements before printing
        const elementsToHide = document.querySelectorAll('.no-print, .header, .sidebar, .footer, .breadcrumb, .btn, .navbar, .modal, .dropdown, .alert');
        elementsToHide.forEach(el => {
            if (el) el.style.display = 'none';
        });

        // Focus on the report content
        const reportContent = document.getElementById('radiology-report-content');
        if (reportContent) {
            reportContent.style.display = 'block';
        }

        // Print the page
        window.print();

        // Restore original title and elements after printing
        setTimeout(() => {
            document.title = originalTitle;
            elementsToHide.forEach(el => {
                if (el) el.style.display = '';
            });
        }, 1000);
    }

    // Add print event listener for better control
    window.addEventListener('beforeprint', function() {
        // Ensure the report is properly formatted before printing
        const reportContainer = document.getElementById('radiology-report-content');
        if (reportContainer) {
            reportContainer.style.background = 'white';
            reportContainer.style.margin = '0';
            reportContainer.style.padding = '20px';
        }
    });

    window.addEventListener('afterprint', function() {
        // Restore any changes made for printing
        const reportContainer = document.getElementById('radiology-report-content');
        if (reportContainer) {
            reportContainer.style.background = '';
            reportContainer.style.margin = '';
            reportContainer.style.padding = '';
        }
    });
</script>
