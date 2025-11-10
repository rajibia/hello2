<!-- Include Pathology Report CSS -->
<link href="{{ asset('assets/css/pathology-report.css') }}" rel="stylesheet">

<div class="pathology-report-container" id="pathology-report-content">
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
                            <img src="{{ $logoUrl }}" alt="Application Logo" style="width: 100%; height: 100%; object-fit: contain; border-radius: 8px;">
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
                    <h4 class="pathology-report-title mb-0" style="font-size: 18px; font-weight: 700; color: #1f2937; text-transform: uppercase; letter-spacing: 0.5px;">
                    {{ getCompanyName() ?? 'CARDINAL NAMDINI MINING LTD, CLINIC LABORATORY' }}
                    </h4>
                </div>
            <!-- Print and PDF Buttons -->
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
        <div class="pathology-admin-details">
            <!-- First Row - Yellow Background -->
            <div class="row mb-2" style="background: #fbbf24; padding: 8px 12px; border-radius: 4px; margin: 0;">
                <div class="col-4">
                    <span class="pathology-admin-label" style="font-weight: 600; color: #92400e; font-size: 12px; text-transform: uppercase;">DATE : {{ $pathologyTest->created_at ? $pathologyTest->created_at->format('d/m/Y') : 'N/A' }}</span>
                </div>
                <div class="col-4 text-center">
                    <span class="pathology-admin-label" style="font-weight: 600; color: #92400e; font-size: 12px; text-transform: uppercase;">SPECIMEN : {{ strtoupper($pathologyTest->pathologyTestItems->first()->pathologytesttemplate->test_type ?? 'BLOOD') }}</span>
                </div>
                <div class="col-4 text-end">
                    <span class="pathology-admin-label" style="font-weight: 600; color: #92400e; font-size: 12px; text-transform: uppercase;">LAB NO : {{ $pathologyTest->lab_number ?? $pathologyTest->bill_no ?? 'N/A' }}</span>
                </div>
            </div>

            <!-- Second Row - White Background -->
            <div class="row mb-2" style="background: white; padding: 8px 12px; border-radius: 4px; margin: 0; border: 1px solid #e5e7eb;">
                <div class="col-6">
                    <span class="pathology-admin-label" style="font-weight: 600; color: #374151; font-size: 12px; text-transform: uppercase;">NAME OF PATIENT : {{ strtoupper($pathologyTest->patient->patientUser->full_name ?? 'N/A') }}</span>
                </div>
                <div class="col-6 text-end">
                    <span class="pathology-admin-label" style="font-weight: 600; color: #374151; font-size: 12px; text-transform: uppercase;">AGE : {{ \Carbon\Carbon::parse($pathologyTest->patient->patientUser->dob ?? now())->age ?? 'N/A' }} YRS</span>
                    <span class="pathology-admin-label ms-3" style="font-weight: 600; color: #374151; font-size: 12px; text-transform: uppercase;">SEX : {{ $pathologyTest->patient->patientUser->gender ? 'F' : 'M' }}</span>
                </div>
            </div>

            <!-- Third Row - White Background -->
            <div class="row mb-2" style="background: white; padding: 8px 12px; border-radius: 4px; margin: 0; border: 1px solid #e5e7eb;">
                <div class="col-6">
                    <span class="pathology-admin-label" style="font-weight: 600; color: #374151; font-size: 12px; text-transform: uppercase;">DIAGNOSIS : {{ strtoupper($pathologyTest->diagnosis ?? 'N/A') }}</span>
                </div>
                <div class="col-6 text-end">
                    <span class="pathology-admin-label" style="font-weight: 600; color: #374151; font-size: 12px; text-transform: uppercase;">TEST REQUESTED :
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
            <div class="row mb-3" style="background: white; padding: 8px 12px; border-radius: 4px; margin: 0; border: 1px solid #e5e7eb;">
                <div class="col-6">
                    <span class="pathology-admin-label" style="font-weight: 600; color: #374151; font-size: 12px; text-transform: uppercase;">NAME OF CLINICIAN : {{ strtoupper($pathologyTest->doctor->doctorUser->full_name ?? 'N/A') }}</span>
                </div>
                <div class="col-6 text-end">
                    <span class="pathology-admin-label" style="font-weight: 600; color: #374151; font-size: 12px; text-transform: uppercase;">TEST PERFORMED BY : {{ strtoupper($pathologyTest->performed_by_user->full_name ?? $pathologyTest->performed_by_user->name ?? 'N/A') }}</span>
                </div>
            </div>
        </div>

        <!-- Test Results Sections for Each Test Item -->
        @if($pathologyTest->pathologyTestItems && $pathologyTest->pathologyTestItems->count() > 0)
            @foreach($pathologyTest->pathologyTestItems as $index => $testItem)
                @php
                    $template = $testItem->pathologytesttemplate;
                    $formConfig = $template ? ($template->form_configuration ?? []) : [];
                    $testResults = $pathologyTest->test_results[$testItem->id] ?? [];
                    $tableType = $formConfig['table_type'] ?? 'standard';
                    $layoutType = $formConfig['layout_type'] ?? 'single_row';
                    $columnsPerRow = $formConfig['columns_per_row'] ?? 1;
                @endphp

                <div class="pathology-results-section">
                    @if($tableType !== 'field_value_multi')
                    <div class="pathology-results-header">
                        <h6 class="mb-0" style="font-size: 14px; font-weight: 700; text-transform: uppercase; color: #202020;">
                            {{ strtoupper($template->test_name ?? 'TEST') }}

                        </h6>
                    </div>
                    @endif

                    @if(!empty($formConfig))
                        @if($tableType === 'field_value_multi')
                            <!-- Field-Value Multi-Column Template Display -->
                            @php
                                $fieldValueConfig = $formConfig['field_value_config'] ?? [];
                                $columnsPerRow = $fieldValueConfig['columns'] ?? 4;
                                $separator = $fieldValueConfig['separator'] ?? ': ';
                                $fields = $formConfig['fields'] ?? [];
                                $specimenName = $formConfig['specimen_name'] ?? $template->test_type ?? 'SPECIMEN';
                            @endphp

                            <!-- Test Results Header - Outside Table -->
                            <div class="text-center">
                                <h5 style="font-weight: bold; color: #92400e; font-size: 16px; margin: 0; padding: 10px 0;">
                                    TEST RESULTS FOR {{ strtoupper($template->test_name ?? 'ROUTINE EXAMINATION') }}
                                </h5>
                            </div>

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

                        @elseif($tableType === 'species_dependent')
                            <!-- Species Dependent Template Display -->
                            @php
                                $speciesConfig = $formConfig['species_config'] ?? [];
                                $results = $speciesConfig['results'] ?? '';
                                $units = $speciesConfig['units'] ?? '';
                                $speciesDependencies = $speciesConfig['species_dependencies'] ?? [];
                                $stageDependencies = $speciesConfig['stage_dependencies'] ?? [];
                            @endphp

                            <div class="table-responsive">
                                <table class="table table-bordered table-sm" style="font-size: 12px; border-collapse: collapse;">
                                    <thead>
                                        <tr style="background-color: #fef3c7;">
                                            <th style="width: 20%; color: #202020; font-weight: 600; text-transform: uppercase; padding: 8px 12px; border: 1px solid #ddd;">RESULTS</th>
                                            <th style="width: 20%; color: #202020; font-weight: 600; text-transform: uppercase; padding: 8px 12px; border: 1px solid #ddd;">SPECIES</th>
                                            <th style="width: 20%; color: #202020; font-weight: 600; text-transform: uppercase; padding: 8px 12px; border: 1px solid #ddd;">STAGE</th>
                                            <th style="width: 20%; color: #202020; font-weight: 600; text-transform: uppercase; padding: 8px 12px; border: 1px solid #ddd;">COUNT</th>
                                            <th style="width: 20%; color: #202020; font-weight: 600; text-transform: uppercase; padding: 8px 12px; border: 1px solid #ddd;">UNIT</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr style="background-color: white;">
                                            <td style="text-align: center; font-weight: 500; color: #374151; padding: 8px 12px; border: 1px solid #ddd;">
                                                @php
                                                    // Get the selected result value
                                                    $selectedResult = $testResults['results'] ?? null;
                                                @endphp
                                                @if($selectedResult && $selectedResult !== '')
                                                    {{ strtoupper($selectedResult) }}
                                                @else
                                                    <span style="color: #9ca3af;">-</span>
                                                @endif
                                            </td>
                                            <td style="text-align: center; font-weight: 500; color: #374151; padding: 8px 12px; border: 1px solid #ddd;">
                                                @php
                                                    // Get the selected species value
                                                    $selectedSpecies = $testResults['species'] ?? null;
                                                @endphp
                                                @if($selectedSpecies && $selectedSpecies !== 'N/A')
                                                    {{ strtoupper($selectedSpecies) }}
                                                @elseif($selectedSpecies === 'N/A')
                                                    <span style="color: #9ca3af;">N/A</span>
                                                @else
                                                    <span style="color: #9ca3af;">-</span>
                                                @endif
                                            </td>
                                            <td style="text-align: center; font-weight: 500; color: #374151; padding: 8px 12px; border: 1px solid #ddd;">
                                                @php
                                                    // Get the selected stage value
                                                    $selectedStage = $testResults['stage'] ?? null;
                                                @endphp
                                                @if($selectedStage && $selectedStage !== 'N/A')
                                                    {{ strtoupper($selectedStage) }}
                                                @elseif($selectedStage === 'N/A')
                                                    <span style="color: #9ca3af;">N/A</span>
                                                @else
                                                    <span style="color: #9ca3af;">-</span>
                                                @endif
                                            </td>
                                            <td style="text-align: center; font-weight: 500; color: #374151; padding: 8px 12px; border: 1px solid #ddd;">
                                                @php
                                                    // Get the count value
                                                    $countValue = $testResults['count'] ?? null;
                                                @endphp
                                                @if($countValue !== null && $countValue !== '')
                                                    {{ $countValue }}
                                                @else
                                                    <span style="color: #9ca3af;">-</span>
                                                @endif
                                            </td>
                                            <td style="text-align: center; font-weight: 500; color: #374151; padding: 8px 12px; border: 1px solid #ddd;">
                                                @php
                                                    // Get the unit value
                                                    $unitValue = $testResults['unit'] ?? null;
                                                @endphp
                                                @if($unitValue !== null && $unitValue !== '')
                                                    {{ strtoupper($unitValue) }}
                                                @else
                                                    <span style="color: #9ca3af;">-</span>
                                                @endif
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>

                        @elseif($tableType === 'specimen')
                            <!-- Specimen Template Display -->
                            @php
                                $specimenName = $formConfig['specimen_name'] ?? $template->test_type ?? 'SPECIMEN';
                                $fields = $formConfig['fields'] ?? [];
                            @endphp

                            <div class="table-responsive">
                                <table class="table table-bordered table-sm" style="font-size: 12px;">
                                    <thead>
                                        <tr style="background-color: #fbbf24; padding: 8px 12px;">
                                            <th style="width: 20%; color: #92400e; font-weight: 600; text-transform: uppercase; padding: 8px 12px;">SPECIMEN</th>
                                            <th style="width: 30%; color: #92400e; font-weight: 600; text-transform: uppercase; padding: 8px 12px;">RESULTS</th>
                                            <th style="width: 25%; color: #92400e; font-weight: 600; text-transform: uppercase; padding: 8px 12px;">REFERENCE RANGE</th>
                                            <th style="width: 15%; color: #92400e; font-weight: 600; text-transform: uppercase; padding: 8px 12px;">FLAG</th>
                                            <th style="width: 10%; color: #92400e; font-weight: 600; text-transform: uppercase; padding: 8px 12px;">UNIT</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if(count($fields) > 0)
                                            @foreach($fields as $field)
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
                                                <td style="font-weight: 600; text-align: left; background: #f8f9fa; padding: 8px 12px;">{{ strtoupper($specimenName) }}</td>
                                                <td style="text-align: center; font-weight: 500; color: #374151; padding: 8px 12px;">
                                                    @if($result !== null && $result !== '')
                                                        {{ strtoupper($result) }}
                                                    @else
                                                        <span style="color: #9ca3af;">-</span>
                                                    @endif
                                                </td>
                                                <td style="text-align: center; font-size: 12px; color: #6b7280; padding: 8px 12px;">
                                                    @if(!empty($field['reference_min']) || !empty($field['reference_max']))
                                                        {{ $field['reference_min'] ?? 'N/A' }} - {{ $field['reference_max'] ?? 'N/A' }}
                                                    @else
                                                        N/A
                                                    @endif
                                                </td>
                                                <td style="text-align: center; padding: 8px 12px;">
                                                    @if($flag)
                                                        <span class="pathology-flag {{ $flagClass }}" style="
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
                                                <td style="text-align: center; font-size: 12px; color: #6b7280; padding: 8px 12px;">
                                                    {{ strtoupper($field['unit'] ?? 'N/A') }}
                                                </td>
                                            </tr>
                                            @endforeach
                                        @else
                                            <tr>
                                                <td colspan="5" class="text-center" style="padding: 20px; color: #7f8c8d;">
                                                    <!-- No fields configured for this template - left blank for clean report appearance -->
                                                </td>
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
                                            <th style="width: 50%; color: #92400e; font-weight: 600; text-transform: uppercase; padding: 8px 12px;">ANALYTE</th>
                                            <th style="width: 50%; color: #92400e; font-weight: 600; text-transform: uppercase; padding: 8px 12px;">RESULTS</th>
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

                        @else
                            <!-- Standard Template Display (ANALYTE, RESULTS, REFERENCE RANGE, FLAG, UNIT) -->
                            @php
                                // Group fields by their group
                                $groupedFields = [];
                                foreach($formConfig['fields'] ?? [] as $field) {
                                    $group = $field['group'] ?? 'General';
                                    if (!isset($groupedFields[$group])) {
                                        $groupedFields[$group] = [];
                                    }
                                    $groupedFields[$group][] = $field;
                                }

                            // Check if any field has reference range data
                            $hasReferenceRange = false;
                            $hasUnit = false;
                                foreach($formConfig['fields'] ?? [] as $field) {
                                if (!empty($field['reference_min']) || !empty($field['reference_max'])) {
                                    $hasReferenceRange = true;
                                }
                                if (!empty($field['unit'])) {
                                    $hasUnit = true;
                                }
                            }
                            @endphp

                            @foreach($groupedFields as $groupName => $groupFields)
                                @if(!empty($groupName) && $groupName !== 'General')
                                    <div class="pathology-results-header" style="background: #fbbf24; color: #92400e; padding: 8px 12px;">
                                        <h6 class="mb-0" style="font-size: 14px; font-weight: 500; text-transform: uppercase;">
                                            {{ strtoupper($groupName) }}
                                        </h6>
                                    </div>
                                @endif

                                @if($layoutType === 'multi_column' && $columnsPerRow > 1)
                                    <!-- Multi-Column Layout for Standard Template -->
                                    @php
                                        $fieldChunks = array_chunk($groupFields, $columnsPerRow);
                                    @endphp
                                    @foreach($fieldChunks as $rowIndex => $row)
                                    <div class="table-responsive" style="margin-bottom: 5px;">
                                        <table class="table table-bordered table-sm" style="font-size: 12px;">
                                            <thead>
                                                <tr style="background: #fbbf24; padding: 8px 12px;">
                                                    @for($i = 0; $i < $columnsPerRow; $i++)
                                                        <th style="width: {{ 100/$columnsPerRow }}%; color: #92400e; font-weight: 600; text-transform: uppercase; padding: 8px 12px;">ANALYTE</th>
                                                        <th style="width: {{ 100/$columnsPerRow }}%; color: #92400e; font-weight: 600; text-transform: uppercase; padding: 8px 12px;">RESULTS</th>
                                                        @if($hasReferenceRange)
                                                            <th style="width: {{ 100/$columnsPerRow }}%; color: #92400e; font-weight: 600; text-transform: uppercase; padding: 8px 12px;">REFERENCE RANGE</th>
                                                            <th style="width: {{ 100/$columnsPerRow }}%; color: #92400e; font-weight: 600; text-transform: uppercase; padding: 8px 12px;">FLAG</th>
                                                        @endif
                                                        @if($hasUnit)
                                                            <th style="width: {{ 100/$columnsPerRow }}%; color: #92400e; font-weight: 600; text-transform: uppercase; padding: 8px 12px;">UNIT</th>
                                                        @endif
                                                    @endfor
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    @foreach($row as $fieldIndex => $field)
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
                                                    <td style="font-weight: 600; text-align: left; background: #f8f9fa; font-size: 11px; padding: 8px 12px;">{{ strtoupper($field['label']) }}</td>
                                                    <td style="text-align: center; font-weight: 500; color: #374151; font-size: 11px; padding: 8px 12px;">
                                                        @if($result !== null && $result !== '')
                                                            {{ strtoupper($result) }}
                                                        @else
                                                            <span style="color: #9ca3af;">-</span>
                                                        @endif
                                                    </td>
                                                    @if($hasReferenceRange)
                                                        <td style="text-align: center; font-size: 10px; color: #6b7280; padding: 8px 12px;">
                                                            @if(!empty($field['reference_min']) || !empty($field['reference_max']))
                                                                {{ $field['reference_min'] ?? 'N/A' }} - {{ $field['reference_max'] ?? 'N/A' }}
                                                            @else
                                                                N/A
                                                            @endif
                                                        </td>
                                                        <td style="text-align: center; padding: 8px 12px;">
                                                            @if($flag)
                                                                <span class="pathology-flag {{ $flagClass }}" style="
                                                                    padding: 1px 4px;
                                                                    border-radius: 2px;
                                                                    font-size: 8px;
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
                                                        <td style="text-align: center; font-size: 10px; color: #6b7280; padding: 8px 12px;">
                                                            {{ strtoupper($field['unit'] ?? 'N/A') }}
                                                        </td>
                                                    @endif
                                                    @endforeach
                                                    @for($i = count($row); $i < $columnsPerRow; $i++)
                                                        <td style="background: #f8f9fa; font-size: 11px; padding: 8px 12px;"></td>
                                                        <td style="font-size: 11px; padding: 8px 12px;"></td>
                                                        @if($hasReferenceRange)
                                                            <td style="font-size: 10px; padding: 8px 12px;"></td>
                                                            <td style="font-size: 10px; padding: 8px 12px;"></td>
                                                        @endif
                                                        @if($hasUnit)
                                                            <td style="font-size: 10px; padding: 8px 12px;"></td>
                                                        @endif
                                                    @endfor
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                    @endforeach
                                @else
                                    <!-- Single Row Layout for Standard Template -->
                                    @php
                            // Determine table class based on columns
                            $tableClass = 'pathology-results-table';
                            if (!$hasReferenceRange && !$hasUnit) {
                                $tableClass .= ' two-columns';
                            } elseif ($hasReferenceRange && !$hasUnit) {
                                $tableClass .= ' three-columns';
                            }
                        @endphp

                                    <div class="table-responsive" style="margin-bottom: 5px;">
                            <table class="{{ $tableClass }}">
                                <thead>
                                                <tr style="background: #fef3c7; padding: 8px 12px;">
                                                    <th style="width: {{ $hasReferenceRange ? '30%' : '50%' }}; color: #202020; font-weight: 600; text-transform: uppercase; padding: 8px 12px;">ANALYTE</th>
                                                    <th style="width: {{ $hasReferenceRange ? '30%' : '50%' }}; color: #202020; font-weight: 600; text-transform: uppercase; padding: 8px 12px;">RESULTS</th>
                                        @if($hasReferenceRange)
                                                        <th style="width: 25%; color: #202020; font-weight: 600; text-transform: uppercase; padding: 8px 12px;">REFERENCE RANGE</th>
                                                        <th style="width: 15%; color: #202020; font-weight: 600; text-transform: uppercase; padding: 8px 12px;">FLAG</th>
                                        @endif
                                        @if($hasUnit)
                                                        <th style="width: 15%; color: #202020; font-weight: 600; text-transform: uppercase; padding: 8px 12px;">UNIT</th>
                                        @endif
                                    </tr>
                                </thead>
                                <tbody>
                                                @foreach($groupFields as $field)
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
                                                    <td style="font-weight: 600; text-align: left; background: #f8f9fa; padding: 8px 12px;">{{ strtoupper($field['label']) }}</td>
                                                    <td style="text-align: center; font-weight: 500; color: #374151; padding: 8px 12px;">
                                            @if($result !== null && $result !== '')
                                                {{ strtoupper($result) }}
                                            @else
                                                <span style="color: #9ca3af;">-</span>
                                            @endif
                                        </td>
                                        @if($hasReferenceRange)
                                                        <td style="text-align: center; font-size: 12px; color: #6b7280; padding: 8px 12px;">
                                                @if(!empty($field['reference_min']) || !empty($field['reference_max']))
                                                    {{ $field['reference_min'] ?? 'N/A' }} - {{ $field['reference_max'] ?? 'N/A' }}
                                                @else
                                                    N/A
                                                @endif
                                            </td>
                                                        <td style="text-align: center; padding: 8px 12px;">
                                                @if($flag)
                                                    <span class="pathology-flag {{ $flagClass }}" style="
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
                                                        <td style="text-align: center; font-size: 12px; color: #6b7280; padding: 8px 12px;">
                                                {{ strtoupper($field['unit'] ?? 'N/A') }}
                                            </td>
                                        @endif
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                                @endif
                            @endforeach
                        @endif
                    @else
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle me-2"></i>
                            No form configuration found for {{ $template->test_name ?? 'this test' }}. Please configure the template with test fields.
                        </div>
                    @endif
                </div>
            @endforeach
        @else
            <div class="alert alert-info">
                <i class="fas fa-info-circle me-2"></i>
                No test items found for this request.
            </div>
        @endif

        <!-- Signature Section -->
        <div class="text-end mt-4" style="border-top: 1px dotted #d1d5db; padding-top: 20px;">
            <div style="display: inline-block; text-align: center;">
                <div style="border-bottom: 1px dotted #d1d5db; width: 200px; height: 40px; margin-bottom: 5px;"></div>
                <small style="color: #6b7280; font-size: 11px;">(Medical Laboratory Technician)</small>
            </div>
        </div>
    </div>
</div>

<style>
/* Basic modal styling */
.modal {
    background-color: rgba(0, 0, 0, 0.5);
    z-index: 1050;
}

.modal-content {
    border-radius: 8px;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.3);
}

.modal-header {
    border-bottom: 1px solid #dee2e6;
    background-color: #f8f9fa;
}

.modal-footer {
    border-top: 1px solid #dee2e6;
    background-color: #f8f9fa;
}

/* Table styling */
.table {
    min-width: 1200px;
}

.table thead th {
    /* background: #202020 !important; */
    color: white;
    border-bottom: 2px solid #dee2e6;
    font-weight: 500;
    text-transform: uppercase;
    font-size: 0.875rem;
    letter-spacing: 0.5px;
    white-space: nowrap;
    padding: 0.75rem 0.5rem;
}

.table tbody td {
    vertical-align: middle;
    padding: 0.75rem 0.6rem;
    white-space: nowrap;
}

.table tbody tr:hover {
    background-color: rgba(0, 123, 255, 0.05);
    transition: background-color 0.2s ease;
}

/* Button styling */
.btn {
    border-radius: 6px;
    font-weight: 500;
    transition: all 0.2s ease;
}

.btn-primary {
    background: linear-gradient(45deg, #007bff, #0056b3);
    border: none;
}

.btn-primary:hover {
    background: linear-gradient(45deg, #0056b3, #004085);
    transform: translateY(-1px);
}

.btn-secondary {
    background: linear-gradient(45deg, #6c757d, #5a6268);
    border: none;
}

.btn-secondary:hover {
    background: linear-gradient(45deg, #5a6268, #495057);
    transform: translateY(-1px);
}

.btn-info {
    background: linear-gradient(45deg, #17a2b8, #138496);
    border: none;
    color: white;
}

.btn-info:hover {
    background: linear-gradient(45deg, #138496, #117a8b);
    transform: translateY(-1px);
    color: white;
}

.btn-danger {
    background: linear-gradient(45deg, #dc3545, #c82333);
    border: none;
}

.btn-danger:hover {
    background: linear-gradient(45deg, #c82333, #bd2130);
    transform: translateY(-1px);
}

.btn-success {
    background: linear-gradient(45deg, #28a745, #20c997);
    border: none;
}

.btn-success:hover {
    background: linear-gradient(45deg, #20c997, #17a2b8);
    transform: translateY(-1px);
}

/* Badge styling */
.badge {
    font-weight: 500;
    padding: 0.5em 0.75em;
    border-radius: 6px;
}

.badge.bg-success {
    background: linear-gradient(45deg, #28a745, #20c997) !important;
}

.badge.bg-danger {
    background: linear-gradient(45deg, #dc3545, #c82333) !important;
}

.badge.bg-warning {
    background: linear-gradient(45deg, #ffc107, #e0a800) !important;
    color: #212529;
}

/* Status improvements */
.status-pending {
    background: linear-gradient(45deg, #ffc107, #e0a800);
    color: #212529;
}

.status-completed {
    background: linear-gradient(45deg, #28a745, #20c997);
    color: white;
}

/* Card styling */
.card {
    border: none;
    border-radius: 12px;
    box-shadow: 0 4px 12px rgba(0,0,0,0.1);
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

        /* Pathology report container */
        .pathology-report-container {
            background: white !important;
            margin: 0 !important;
            padding: 20px !important;
            box-shadow: none !important;
            border: none !important;
        }

    /* Header styling for print */
        .pathology-report-header {
            background: white !important;
            padding: 20px 25px 15px 25px !important;
            border-bottom: 2px solid #e5e7eb !important;
            margin-bottom: 20px !important;
        }

        .pathology-report-title {
            color: #1f2937 !important;
            font-weight: 700 !important;
            font-size: 18px !important;
            text-transform: uppercase !important;
            letter-spacing: 0.5px !important;
            margin: 0 !important;
        }

        .pathology-report-subtitle {
            color: #1e40af !important;
            font-weight: 600 !important;
            font-size: 16px !important;
            text-transform: uppercase !important;
            letter-spacing: 1px !important;
            margin: 0 !important;
        }

    /* Administrative details for print */
        .pathology-admin-details {
            margin-bottom: 25px !important;
        }

        .pathology-admin-details .row {
            margin-bottom: 10px !important;
            page-break-inside: avoid !important;
        }

        /* First row - Yellow background */
        .pathology-admin-details .row:first-child {
            background: #fbbf24 !important;
            background-color: #fbbf24 !important;
            padding: 8px 12px !important;
            border-radius: 4px !important;
            margin: 0 !important;
        }

        /* White background rows */
        .pathology-admin-details .row:not(:first-child) {
            background: white !important;
            background-color: white !important;
            padding: 8px 12px !important;
            border-radius: 4px !important;
            border: 1px solid #e5e7eb !important;
        margin: 0 !important;
    }

    /* Test results headers for print */
        .pathology-results-header {
            background: #fbbf24 !important;
            background-color: #fbbf24 !important;
            padding: 12px 20px !important;
            border-bottom: 2px solid #f59e0b !important;
        }

        .pathology-results-header h6 {
            color: #92400e !important;
            font-weight: 700 !important;
            font-size: 14px !important;
            text-transform: uppercase !important;
            letter-spacing: 0.5px !important;
            margin: 0 !important;
        }

    /* Group headers for print */
    .pathology-results-header[style*="background: #fff1cc"] {
        background: #fff1cc !important;
        background-color: #fff1cc !important;
    }

    .pathology-results-header[style*="background: #fff1cc"] h6 {
        color: #1e40af !important;
    }

    /* Table headers for print */
    .pathology-results-table thead tr {
        background: #fff1cc !important;
        background-color: #fff1cc !important;
    }

    .pathology-results-table thead th {
        color: #1e40af !important;
            font-weight: 600 !important;
            text-transform: uppercase !important;
        border: 1px solid #e5e7eb !important;
        padding: 8px 12px !important;
    }

    /* Table styling for print */
    .pathology-results-table {
        width: 100% !important;
        border-collapse: collapse !important;
        margin-bottom: 15px !important;
    }

    .pathology-results-table th,
        .pathology-results-table td {
            border: 1px solid #e5e7eb !important;
        padding: 8px 12px !important;
        text-align: left !important;
            vertical-align: middle !important;
        }

    .pathology-results-table tbody tr:nth-child(even) {
            background: #f8f9fa !important;
        }

    /* Flag styling for print */
        .pathology-flag {
        padding: 2px 8px !important;
            border-radius: 3px !important;
            font-size: 10px !important;
            font-weight: 600 !important;
            text-transform: uppercase !important;
        }

        .flag-low {
            background: #fef3c7 !important;
            color: #92400e !important;
        }

        .flag-high {
            background: #fee2e2 !important;
            color: #991b1b !important;
        }

        .flag-normal {
            background: #d1fae5 !important;
            color: #065f46 !important;
        }

    /* Signature section for print */
        .signature-section {
            margin-top: 30px !important;
            padding-top: 20px !important;
            border-top: 2px solid #e5e7eb !important;
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
}
</style>

<script>
function printLaboratoryReport() {
    console.log('Print function called');

    const reportContent = document.getElementById('pathology-report-content');
    if (!reportContent) {
        console.error('Report content not found');
        alert('Report content not found.');
        return;
    }

    console.log('Report content found, proceeding with print');

    const originalTitle = document.title;
    document.title = 'Laboratory Test Report - {{ $pathologyTest->lab_number ?? $pathologyTest->bill_no ?? "Report" }}';

    // Create a clone of the content to avoid modifying the original
    const contentClone = reportContent.cloneNode(true);

    // Remove print buttons and other no-print elements from the clone
    const noPrintElements = contentClone.querySelectorAll('.no-print, .btn, button');
    noPrintElements.forEach(element => element.remove());

        // Ensure the header structure is preserved for print
    const headerSection = contentClone.querySelector('.pathology-report-header');
    if (headerSection) {
        // Ensure the company name and subtitle are properly displayed
        const titleElement = headerSection.querySelector('.pathology-report-title');
        const subtitleElement = headerSection.querySelector('.pathology-report-subtitle');
        const logoElement = headerSection.querySelector('.me-3');

        if (titleElement) {
            titleElement.style.display = 'block';
            titleElement.style.visibility = 'visible';
            titleElement.style.fontSize = '18px';
            titleElement.style.fontWeight = '700';
            titleElement.style.color = '#1f2937';
            titleElement.style.textTransform = 'uppercase';
            titleElement.style.letterSpacing = '0.5px';
        }

        if (subtitleElement) {
            subtitleElement.style.display = 'block';
            subtitleElement.style.visibility = 'visible';
            subtitleElement.style.fontSize = '16px';
            subtitleElement.style.fontWeight = '600';
            subtitleElement.style.color = '#1e40af';
            subtitleElement.style.textTransform = 'uppercase';
            subtitleElement.style.letterSpacing = '1px';
        }

        if (logoElement) {
            logoElement.style.display = 'block';
            logoElement.style.visibility = 'visible';
        }

        // Ensure the flex layout is preserved
        const flexContainer = headerSection.querySelector('.d-flex');
        if (flexContainer) {
            flexContainer.style.display = 'flex';
            flexContainer.style.justifyContent = 'space-between';
            flexContainer.style.alignItems = 'center';
            flexContainer.style.marginBottom = '15px';
        }

        const centerContainer = headerSection.querySelector('.text-center');
        if (centerContainer) {
            centerContainer.style.textAlign = 'center';
            centerContainer.style.flexGrow = '1';
        }
    }

    // Ensure administrative details section is properly preserved for print
    const adminDetailsSection = contentClone.querySelector('.pathology-admin-details');
    if (adminDetailsSection) {
        // Ensure all rows are properly displayed
        const rows = adminDetailsSection.querySelectorAll('.row');
        rows.forEach((row, index) => {
            row.style.display = 'flex';
            row.style.flexWrap = 'wrap';
            row.style.margin = '0 0 8px 0';

            if (index === 0) {
                // First row (yellow background)
                row.style.background = '#fbbf24';
                row.style.backgroundColor = '#fbbf24';
                row.style.padding = '8px 12px';
                row.style.borderRadius = '4px';
            } else {
                // Other rows (white background with border)
                row.style.background = 'white';
                row.style.backgroundColor = 'white';
                row.style.padding = '8px 12px';
                row.style.borderRadius = '4px';
                row.style.border = '1px solid #e5e7eb';
            }
        });

        // Ensure columns are properly sized
        const cols = adminDetailsSection.querySelectorAll('.col-4, .col-6');
        cols.forEach(col => {
            col.style.padding = '0 8px';
            if (col.classList.contains('col-4')) {
                col.style.flex = '0 0 33.333333%';
                col.style.maxWidth = '33.333333%';
            } else if (col.classList.contains('col-6')) {
                col.style.flex = '0 0 50%';
                col.style.maxWidth = '50%';
            }
        });

        // Ensure text alignment is preserved
        const textEndElements = adminDetailsSection.querySelectorAll('.text-end');
        textEndElements.forEach(element => {
            element.style.textAlign = 'right';
        });

        const textCenterElements = adminDetailsSection.querySelectorAll('.text-center');
        textCenterElements.forEach(element => {
            element.style.textAlign = 'center';
        });
    }

    // Ensure table styling is correct for print
    const tables = contentClone.querySelectorAll('.pathology-results-table');
    tables.forEach(table => {
        // Style table headers
        const headers = table.querySelectorAll('thead th');
        headers.forEach(header => {
            header.style.background = '#fbbf24';
            header.style.backgroundColor = '#fbbf24';
            header.style.color = '#202020';
            header.style.fontWeight = '700';
            header.style.fontSize = '12px';
            header.style.textTransform = 'uppercase';
            header.style.letterSpacing = '0.5px';
            header.style.padding = '8px';
            header.style.border = '1px solid #d1d5db';
            header.style.textAlign = 'center';
        });

        // Style table rows with alternating colors
        const rows = table.querySelectorAll('tbody tr');
        rows.forEach((row, index) => {
            if (index % 2 === 0) {
                // Odd rows (first, third, etc.) - yellow background
                row.style.background = '#fbbf24';
                row.style.backgroundColor = '#fbbf24';
            } else {
                // Even rows (second, fourth, etc.) - white background
                row.style.background = 'white';
                row.style.backgroundColor = 'white';
            }

            // Style all cells in the row
            const cells = row.querySelectorAll('td');
            cells.forEach(cell => {
                cell.style.border = '1px solid #e5e7eb';
                cell.style.padding = '8px';
                cell.style.textAlign = 'left';
                cell.style.fontSize = '12px';
                cell.style.verticalAlign = 'middle';
            });
        });
    });

    const printHTML = `
        <!DOCTYPE html>
        <html>
        <head>
            <title>${document.title}</title>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <style>
                * {
                    -webkit-print-color-adjust: exact !important;
                    color-adjust: exact !important;
                    print-color-adjust: exact !important;
                    -webkit-box-sizing: border-box !important;
                    box-sizing: border-box !important;
                }

                body {
                    margin: 0 !important;
                    padding: 20px !important;
                    background: white !important;
                    font-size: 12px !important;
                    line-height: 1.4 !important;
                    font-family: Arial, sans-serif !important;
                    color: #000 !important;
                }

                .pathology-report-container {
                    background: white !important;
                    margin: 0 !important;
                    padding: 0 !important;
            box-shadow: none !important;
                    border: none !important;
                    max-width: none !important;
                }

                .pathology-report-header {
                    background: white !important;
                    padding: 20px 25px 15px 25px !important;
                    border-bottom: 2px solid #e5e7eb !important;
                    margin-bottom: 20px !important;
                    page-break-after: avoid !important;
                    display: block !important;
                    visibility: visible !important;
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

                .pathology-report-title {
                    color: #1f2937 !important;
                    font-weight: 700 !important;
                    font-size: 18px !important;
                    text-transform: uppercase !important;
                    letter-spacing: 0.5px !important;
                    margin: 0 !important;
                    display: block !important;
                    visibility: visible !important;
                }

                .pathology-report-subtitle {
                    color: #1e40af !important;
                    font-weight: 600 !important;
                    font-size: 16px !important;
                    text-transform: uppercase !important;
                    letter-spacing: 1px !important;
                    margin: 0 !important;
                    display: block !important;
                    visibility: visible !important;
                }

                .pathology-report-body {
                    padding: 0 25px !important;
                    background: white !important;
                }

                .pathology-admin-details {
                    margin-bottom: 25px !important;
                }

                .pathology-admin-details {
                    margin-bottom: 25px !important;
                }

                .pathology-admin-details .row {
                    margin: 0 0 8px 0 !important;
                    display: flex !important;
                    flex-wrap: wrap !important;
                }

                .pathology-admin-details .row:first-child {
            background: #fbbf24 !important;
            background-color: #fbbf24 !important;
                    padding: 8px 12px !important;
                    border-radius: 4px !important;
                    margin: 0 0 8px 0 !important;
                }

                .pathology-admin-details .row:not(:first-child) {
                    background: white !important;
                    background-color: white !important;
                    padding: 8px 12px !important;
                    border-radius: 4px !important;
                    border: 1px solid #e5e7eb !important;
                    margin: 0 0 8px 0 !important;
                }

                .pathology-admin-details .col-4,
                .pathology-admin-details .col-6 {
                    padding: 0 8px !important;
                    flex: 1 !important;
                }

                .pathology-admin-details .col-4 {
                    flex: 0 0 33.333333% !important;
                    max-width: 33.333333% !important;
                }

                .pathology-admin-details .col-6 {
                    flex: 0 0 50% !important;
                    max-width: 50% !important;
                }

                .pathology-admin-details .text-end {
                    text-align: right !important;
                }

                .pathology-admin-details .text-center {
                    text-align: center !important;
                }

                .pathology-admin-label {
                    font-weight: 600 !important;
                    font-size: 12px !important;
                    text-transform: uppercase !important;
                    letter-spacing: 0.3px !important;
                }

                .pathology-admin-details .row:first-child .pathology-admin-label {
                    color: #92400e !important;
                }

                .pathology-admin-details .row:not(:first-child) .pathology-admin-label {
                    color: #374151 !important;
                }

                .pathology-results-section {
                    background: white !important;
                    border-radius: 8px !important;
                    box-shadow: none !important;
                    margin-bottom: 20px !important;
                    overflow: visible !important;
                    page-break-inside: avoid !important;
                }

                .pathology-results-header {
            background: #fbbf24 !important;
            background-color: #fbbf24 !important;
                    padding: 10px 20px !important;
                    text-align: center !important;
        }

        .pathology-results-header h6 {
                    color: #202020 !important;
                    font-weight: 700 !important;
                    font-size: 14px !important;
                    text-transform: uppercase !important;
                    letter-spacing: 0.5px !important;
                    margin: 0 !important;
                }



                .pathology-results-table {
                    width: 100% !important;
                    border-collapse: collapse !important;
                    margin-bottom: 15px !important;
                    background: white !important;
                }

                .pathology-results-table thead tr {
                    background: #fbbf24 !important;
                    background-color: #fbbf24 !important;
                }

                .pathology-results-table thead th {
                    color: #202020 !important;
                    font-weight: 700 !important;
                    font-size: 12px !important;
                    text-transform: uppercase !important;
                    letter-spacing: 0.5px !important;
                    border: 1px solid #d1d5db !important;
                    padding: 8px !important;
                    text-align: center !important;
                }

                .pathology-results-table tbody td {
                    border: 1px solid #e5e7eb !important;
                    padding: 8px !important;
                    text-align: left !important;
                    vertical-align: middle !important;
                    font-size: 12px !important;
                }

                .pathology-results-table tbody tr:nth-child(odd) {
                    background: #fbbf24 !important;
                    background-color: #fbbf24 !important;
                }

                .pathology-results-table tbody tr:nth-child(even) {
                    background: white !important;
                    background-color: white !important;
                }

                .pathology-flag {
                    padding: 2px 8px !important;
                    border-radius: 3px !important;
                    font-size: 10px !important;
                    font-weight: 600 !important;
                    text-transform: uppercase !important;
                }

                .flag-low {
                    background: #fef3c7 !important;
                    color: #92400e !important;
                }

                .flag-high {
                    background: #fee2e2 !important;
                    color: #991b1b !important;
                }

                .flag-normal {
                    background: #d1fae5 !important;
                    color: #065f46 !important;
                }

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

                /* Hide elements that shouldn't be printed */
                .no-print, .btn, button, .modal-backdrop, .modal-header, .btn-close {
                    display: none !important;
                }

                /* Ensure header elements are visible in print */
                .pathology-report-header,
                .pathology-report-header *,
                .pathology-report-title,
                .pathology-report-subtitle {
                    display: block !important;
                    visibility: visible !important;
                }

                /* Ensure administrative details section is properly displayed */
                .pathology-admin-details,
                .pathology-admin-details *,
                .pathology-admin-details .row,
                .pathology-admin-details .col-4,
                .pathology-admin-details .col-6 {
                    display: block !important;
                    visibility: visible !important;
                }

                .pathology-admin-details .row {
                    display: flex !important;
                    flex-wrap: wrap !important;
                }

                .pathology-admin-details .col-4 {
                    flex: 0 0 33.333333% !important;
                    max-width: 33.333333% !important;
                }

                .pathology-admin-details .col-6 {
                    flex: 0 0 50% !important;
                    max-width: 50% !important;
                }

                /* Ensure proper page breaks */
                .pathology-results-section {
                    page-break-inside: avoid !important;
                }

                /* Remove any shadows or effects */
                * {
                    box-shadow: none !important;
                    text-shadow: none !important;
                }

                /* Ensure proper spacing */
                .row {
                    margin: 0 !important;
                }

                .col-4, .col-6, .col-12 {
                    padding: 0 8px !important;
                }

                /* Ensure text is readable */
                * {
                    color-adjust: exact !important;
                    -webkit-print-color-adjust: exact !important;
                    print-color-adjust: exact !important;
                }
            </style>
        </head>
        <body>
            ${contentClone.innerHTML}
        </body>
        </html>
    `;

    const printWindow = window.open('', '_blank', 'width=800,height=600,scrollbars=yes,resizable=yes');

    if (!printWindow) {
        alert('Please allow pop-ups for this site to print the report.');
        return;
    }

    printWindow.document.write(printHTML);
    printWindow.document.close();

    // Wait for content to load before printing
    printWindow.onload = function() {
    printWindow.focus();
        setTimeout(function() {
    printWindow.print();
    printWindow.close();
        }, 500);
    };

    document.title = originalTitle;
}

// Fallback print function using browser's native print
function printLaboratoryReportFallback() {
    console.log('Fallback print function called');

    const reportContent = document.getElementById('pathology-report-content');
    if (!reportContent) {
        console.error('Report content not found in fallback');
        alert('Report content not found.');
        return;
    }

    console.log('Report content found in fallback, proceeding with print');

    // Hide print buttons temporarily
    const printButtons = document.querySelectorAll('.no-print, .btn, button');
    const originalDisplays = [];
    printButtons.forEach((button, index) => {
        originalDisplays[index] = button.style.display;
        button.style.display = 'none';
    });

    // Print the current page
    window.print();

    // Restore buttons
    printButtons.forEach((button, index) => {
        button.style.display = originalDisplays[index];
    });
}

// Add keyboard shortcut for printing (Ctrl+P)
document.addEventListener('keydown', function(e) {
    if (e.ctrlKey && e.key === 'p') {
        e.preventDefault();
        printLaboratoryReport();
    }
});
</script>
