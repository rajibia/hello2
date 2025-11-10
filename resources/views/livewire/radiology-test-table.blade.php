<div>
    <!-- Success Messages -->
    @if(session()->has('message'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('message') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    <!-- Error Messages -->
    @if(session()->has('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
        <h4 class="mb-0">Radiology Tests</h4>
            @if($ipdId)
                <small class="text-muted">Showing all radiology tests for this IPD patient ({{ $tests->count() }} found)</small>
            @endif
        </div>
        @if(!$ipdId)
        <button wire:click="create" class="btn btn-primary">
            <i class="fas fa-plus me-2"></i>New Test Request
        </button>
        @endif
    </div>

    <!-- Search -->
    <div class="row mb-4">
        <div class="col-md-6">
            <div class="input-group">
                <input type="text" wire:model.debounce.300ms="search" class="form-control" placeholder="Search by bill number, doctor name, or test name...">
                <span class="input-group-text">
                    <i class="fas fa-search"></i>
                </span>
            </div>
        </div>
    </div>

    <!-- Tests Table -->
    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead class="table-light">
                        <tr>
                            <th>Bill No</th>
                            <th>Patient</th>
                            <th>Test</th>
                            <th>Doctor</th>
                            <th>Status</th>
                            <th>Discount %</th>
                            <th>Amount</th>
                            <th>Amount Paid</th>
                            <th>Balance</th>
                            <th>Payment Status</th>
                            <th>Date</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($tests as $test)
                        <tr>
                            <td>
                                <a href="{{ route('radiology.test.show', $test->id) }}" class="fw-semibold text-primary text-decoration-none">
                                    {{ $test->bill_no }}
                                </a>
                            </td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="avatar avatar-sm me-3">
                                        @if($test->patient && $test->patient->patientUser && $test->patient->patientUser->profile_photo_url)
                                            <img src="{{ $test->patient->patientUser->profile_photo_url }}" class="rounded-circle" width="32" height="32" alt="Patient">
                                        @else
                                            <i class="fas fa-user-circle fa-2x text-primary"></i>
                                        @endif
                                    </div>
                                    <div>
                                        <div class="fw-semibold">{{ $test->patient->patientUser->full_name ?? 'N/A' }}</div>
                                        <small class="text-muted">{{ $test->patient->patientUser->email ?? '' }}</small>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div>
                                    @if($test->radiologyTestItems && $test->radiologyTestItems->count() > 0)
                                        <div class="fw-semibold">{{ $test->radiologyTestItems->count() }} Test(s)</div>
                                        <small class="text-muted">
                                            @foreach($test->radiologyTestItems->take(2) as $item)
                                                @php
                                                    $template = $item->radiologytesttemplate;
                                                    $templateType = '';
                                                    $templateTypeClass = 'bg-secondary';

                                                    if ($template && $template->is_dynamic_form && $template->form_configuration) {
                                                        $tableType = $template->form_configuration['table_type'] ?? 'standard';
                                                        switch($tableType) {
                                                            case 'standard':
                                                                $templateType = 'Standard';
                                                                $templateTypeClass = 'bg-primary';
                                                                break;
                                                            case 'simple':
                                                                $templateType = 'Simple';
                                                                $templateTypeClass = 'bg-success';
                                                                break;
                                                            case 'specimen':
                                                                $templateType = 'Specimen';
                                                                $templateTypeClass = 'bg-warning';
                                                                break;
                                                            case 'species_dependent':
                                                                $templateType = 'Species';
                                                                $templateTypeClass = 'bg-info';
                                                                break;
                                                            case 'field_value_multi':
                                                                $templateType = 'Field-Value';
                                                                $templateTypeClass = 'bg-secondary';
                                                                break;
                                                        }
                                                    }
                                                @endphp
                                                <div class="d-flex align-items-center">
                                                    <span>{{ $template->test_name ?? 'N/A' }}</span>
                                                    @if($templateType)
                                                        <span class="badge {{ $templateTypeClass }} ms-1" style="font-size: 10px;">{{ $templateType }}</span>
                                                    @endif
                                                </div>{{ !$loop->last ? ', ' : '' }}
                                            @endforeach
                                            @if($test->radiologyTestItems->count() > 2)
                                                +{{ $test->radiologyTestItems->count() - 2 }} more
                                            @endif
                                        </small>
                                    @else
                                        <div class="fw-semibold">No Tests</div>
                                        <small class="text-muted">No test items found</small>
                                    @endif
                                </div>
                            </td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="avatar avatar-sm me-3">
                                        @if($test->doctor && $test->doctor->doctorUser && $test->doctor->doctorUser->profile_photo_url)
                                            <img src="{{ $test->doctor->doctorUser->profile_photo_url }}" class="rounded-circle" width="32" height="32" alt="Doctor">
                                        @else
                                            <i class="fas fa-user-md fa-2x text-info"></i>
                                        @endif
                                    </div>
                                    <div>
                                        <div class="fw-semibold">{{ $test->doctor->doctorUser->full_name ?? 'N/A' }}</div>
                                        <small class="text-muted">{{ $test->doctor->doctorUser->email ?? '' }}</small>
                                    </div>
                                </div>
                            </td>
                            <td>
                                @if($test->status == 0)
                                    <span class="badge bg-warning">Pending</span>
                                @elseif($test->status == 1)
                                    <span class="badge bg-info">In Progress</span>
                                @else
                                    <span class="badge bg-success">Completed</span>
                                @endif
                            </td>
                            <td>
                                <span class="fw-semibold">{{ $test->discount ?? '0' }}%</span>
                            </td>
                            <td>
                                <span class="fw-semibold text-primary">{{ number_format($test->total, 2) }}</span>
                            </td>
                            <td>
                                <span class="fw-semibold text-success">{{ number_format($test->amount_paid, 2) }}</span>
                            </td>
                            <td>
                                <span class="fw-semibold text-danger">{{ number_format($test->balance, 2) }}</span>
                            </td>
                            <td>
                                @if($test->balance == 0)
                                    <span class="badge bg-success">Paid</span>
                                @elseif($test->amount_paid > 0)
                                    <span class="badge bg-warning">Partial</span>
                                @else
                                    <span class="badge bg-danger">Unpaid</span>
                                @endif
                            </td>
                            <td>
                                <div>
                                    <div class="fw-semibold">{{ $test->created_at ? $test->created_at->format('d/m/Y') : 'N/A' }}</div>
                                    <small class="text-muted">{{ $test->created_at ? $test->created_at->format('H:i') : '' }}</small>
                                </div>
                            </td>
                            <td>
                                <div class="btn-group" role="group">
                                    @php
                                        $balance = (float)($test->balance ?? 0);
                                        $isPaid = $balance <= 0;
                                        $isCompanyPatient = $test->patient && $test->patient->company_id;
                                        $canViewOrEdit = $isPaid || $isCompanyPatient;
                                    @endphp

                                    <!-- View Results Button -->
                                    @if($canViewOrEdit)
                                        <button wire:click="viewResults({{ $test->id }})" class="btn btn-sm btn-primary" title="View Results">
                                            <i class="fas fa-eye"></i> View
                                        </button>
                                    @else
                                        <button class="btn btn-sm btn-secondary" disabled title="Bill not paid - Outstanding: GHS {{ number_format($balance, 2) }}">
                                            <i class="fas fa-lock"></i>
                                        </button>
                                    @endif

                                    <!-- Edit Button -->
                                    @if(!$isPaid)
                                        <button wire:click="edit({{ $test->id }})" class="btn btn-sm btn-info" title="Edit Test">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                    @endif

                                    <!-- Delete Button -->
                                    <button wire:click="delete({{ $test->id }})" class="btn btn-sm btn-danger" title="Delete Test" onclick="return confirm('Are you sure you want to delete this test request?')">
                                        <i class="fas fa-trash"></i>
                                    </button>

                                    <!-- Payment Icon - Only show for individual patients with balance -->
                                    @if($balance > 0 && !$isCompanyPatient)
                                            <a target="_blank" href="{{ route('patient.bills.show', $test->patient_id) }}" title="View Patient Bills" class="btn btn-sm btn-warning">
                                                <i class="fa fa-credit-card"></i>
                                            </a>
                                        @endif

                                    <!-- Company Bills Icon - Only show for company patients with balance -->
                                    @if($balance > 0 && $isCompanyPatient)
                                        <a target="_blank" href="{{ route('companies.view', $test->patient->company_id) }}" title="View Company Bills" class="btn btn-sm btn-warning">
                                            <i class="fa fa-building"></i>
                                        </a>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="11" class="text-center">
                                <div class="py-4">
                                    <i class="fas fa-x-ray fa-3x text-muted mb-3"></i>
                                    <h5 class="text-muted">No Radiology Tests Found</h5>
                                    @if($ipdId)
                                        <p class="text-muted">No radiology tests found for this IPD patient.</p>
                                    @else
                                    <p class="text-muted">No radiology tests have been created yet.</p>
                                    <button wire:click="create" class="btn btn-primary">
                                        <i class="fas fa-plus me-2"></i>Create First Test
                                    </button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

                        <!-- Pagination - Only show for non-IPD context -->
            @if(!$ipdId && $tests->hasPages())
                <div class="d-flex justify-content-center mt-4">
                    {{ $tests->links('vendor.pagination.bootstrap-4') }}
            </div>
            @endif
        </div>
    </div>

    <!-- Loading Indicator -->
    @if($loading)
        <div class="position-fixed top-50 start-50 translate-middle">
            <div class="spinner-border text-primary" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
        </div>
    @endif

    <!-- Create Modal -->
    @if($showCreateModal)
    <div class="modal fade show" style="display: block;" tabindex="-1">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title">
                        <i class="fas fa-plus me-2"></i>Create New Radiology Test Request
                    </h5>
                    <button type="button" class="btn-close btn-close-white" wire:click="cancel">
                        <span>&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    @include('livewire.create-radiology-test', ['available_templates' => $available_templates])
                </div>
            </div>
        </div>
    </div>
    @endif
</div>

<!-- Results Modal -->
@if($showResultsModal && $selectedTest)
<div class="modal-backdrop fade show" style="z-index: 9998; position: fixed; top: 0; left: 0; width: 100vw; height: 100vh; background-color: rgba(0, 0, 0, 0.5);"></div>
<div class="modal fade show pathology-report-modal" style="display: block; z-index: 9999; position: fixed; top: 0; left: 0; width: 100%; height: 100%;" tabindex="-1">
    <div class="modal-dialog modal-xl" style="margin: 1.75rem auto; max-width: 90%;">
        <div class="modal-content">
            <!-- Include Pathology Report CSS -->
            <link href="{{ asset('assets/css/pathology-report.css') }}" rel="stylesheet">

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

                    <button type="button" class="btn-close" wire:click="$set('showResultsModal', false)" style="background: none; border: none; font-size: 20px; color: #6b7280; cursor: pointer;">&times;</button>
                </div>
                <div class="text-center">
                    <h5 class="pathology-report-subtitle mb-0" style="font-size: 16px; font-weight: 600; color: #1e40af; text-transform: uppercase; letter-spacing: 1px;">
                        RADIOLOGY RESULTS
                    </h5>
                </div>
            </div>

            <div class="modal-body pathology-report-body">
                <div class="pathology-report-container" id="pathology-report-content">
                <!-- Modal Messages Section -->
                @if(empty($selectedTest->lab_number) || empty($diagnosis))
                    <div class="alert alert-danger alert-dismissible fade show mb-3" role="alert" id="validationAlert">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        @if(empty($selectedTest->lab_number) && empty($diagnosis))
                            Please fill in the required fields: LAB NO and DIAGNOSIS
                        @elseif(empty($selectedTest->lab_number))
                            Please fill in the required field: LAB NO
                        @elseif(empty($diagnosis))
                            Please fill in the required field: DIAGNOSIS
                        @endif
                        <button type="button" class="btn-close" onclick="document.getElementById('validationAlert').style.display='none';" aria-label="Close"></button>
                    </div>
                @elseif($modalMessage)
                    <div class="alert alert-{{ $modalMessageType === 'success' ? 'success' : 'danger' }} alert-dismissible fade show mb-3" role="alert">
                        <i class="fas fa-{{ $modalMessageType === 'success' ? 'check-circle' : 'exclamation-triangle' }} me-2"></i>{{ $modalMessage }}
                        <button type="button" class="btn-close" wire:click="$set('modalMessage', '')" aria-label="Close"></button>
                    </div>
                @endif

                <!-- Administrative Details Section -->
                <div class="pathology-admin-details">
                    <!-- First Row - Yellow Background -->
                    <div class="row mb-2" style="background: #fbbf24; padding: 8px 12px; border-radius: 4px; margin: 0;">
                        <div class="col-4">
                            <span class="pathology-admin-label" style="font-weight: 600; color: #92400e; font-size: 12px; text-transform: uppercase;">DATE : {{ $selectedTest->created_at ? $selectedTest->created_at->format('d/m/Y') : 'N/A' }}</span>
                        </div>
                        <div class="col-4 text-center">
                            <span class="pathology-admin-label" style="font-weight: 600; color: #92400e; font-size: 12px; text-transform: uppercase;">SPECIMEN : RADIOLOGY</span>
                        </div>
                        <div class="col-4 text-end">
                            <span class="pathology-admin-label" style="font-weight: 600; color: #92400e; font-size: 12px; text-transform: uppercase;">LAB NO : <span class="text-danger">*</span></span>
                            <input type="text" wire:model.live="selectedTest.lab_number" style="border: none; background: transparent; font-weight: 600; color: #92400e; font-size: 12px; text-transform: uppercase; width: 120px; text-align: right;" placeholder="ENTER LAB NO">
                        </div>
                    </div>

                    <!-- Second Row - White Background -->
                    <div class="row mb-2" style="background: white; padding: 8px 12px; border-radius: 4px; margin: 0; border: 1px solid #e5e7eb;">
                        <div class="col-6">
                            <span class="pathology-admin-label" style="font-weight: 600; color: #374151; font-size: 12px; text-transform: uppercase;">NAME OF PATIENT : {{ strtoupper($selectedTest->patient->patientUser->full_name ?? 'N/A') }}</span>
                            </div>
                        <div class="col-6 text-end">
                            <span class="pathology-admin-label" style="font-weight: 600; color: #374151; font-size: 12px; text-transform: uppercase;">AGE : {{ \Carbon\Carbon::parse($selectedTest->patient->patientUser->dob ?? now())->age ?? 'N/A' }} YRS</span>
                            <span class="pathology-admin-label ms-3" style="font-weight: 600; color: #374151; font-size: 12px; text-transform: uppercase;">SEX : {{ $selectedTest->patient->patientUser->gender ? 'F' : 'M' }}</span>
                            </div>
                            </div>

                    <!-- Third Row - White Background -->
                    <div class="row mb-2" style="background: white; padding: 8px 12px; border-radius: 4px; margin: 0; border: 1px solid #e5e7eb;">
                        <div class="col-6">
                            <span class="pathology-admin-label" style="font-weight: 600; color: #374151; font-size: 12px; text-transform: uppercase;">DIAGNOSIS : <span class="text-danger">*</span></span>
                            <input type="text" wire:model.live="diagnosis" style="border: none; background: transparent; font-weight: 600; color: #374151; font-size: 12px; text-transform: uppercase; width: 200px;" placeholder="ENTER DIAGNOSIS">
                        </div>
                        <div class="col-6 text-end">
                            <span class="pathology-admin-label" style="font-weight: 600; color: #374151; font-size: 12px; text-transform: uppercase;">TEST REQUESTED :
                                @if($selectedTest->radiologyTestItems && $selectedTest->radiologyTestItems->count() > 0)
                                    @php
                                        $testNames = $selectedTest->radiologyTestItems->pluck('radiologytesttemplate.test_name')->filter()->toArray();
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
                            <span class="pathology-admin-label" style="font-weight: 600; color: #374151; font-size: 12px; text-transform: uppercase;">NAME OF CLINICIAN : {{ strtoupper($selectedTest->doctor->doctorUser->full_name ?? 'N/A') }}</span>
                            </div>
                        <div class="col-6 text-end">
                            <span class="pathology-admin-label" style="font-weight: 600; color: #374151; font-size: 12px; text-transform: uppercase;">TEST PERFORMED BY :</span>
                            <input type="text" wire:model.live="performed_by" style="border: none; background: transparent; font-weight: 600; color: #374151; font-size: 12px; text-transform: uppercase; width: 200px; text-align: right;" placeholder="ENTER NAME" value="{{ strtoupper($selectedTest->performed_by_user->full_name ?? $selectedTest->performed_by_user->name ?? auth()->user()->full_name ?? auth()->user()->name ?? '') }}">
                        </div>
                    </div>
                </div>

                <!-- Test Results Sections for Each Test Item -->
                @if($selectedTest->radiologyTestItems && $selectedTest->radiologyTestItems->count() > 0)
                    @foreach($selectedTest->radiologyTestItems as $index => $testItem)
                        @php
                            $template = $testItem->radiologytesttemplate;
                            $formConfig = $template ? ($template->form_configuration ?? []) : [];
                            $testResults = $selectedTest->test_results[$testItem->id] ?? [];
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
                                                                <div style="margin-top: 4px;">
                                                                    @if(($field['type'] ?? '') == 'dropdown')
                                                                        <select wire:model.live="test_results.{{ $testItem->id }}.{{ $field['name'] }}" style="border: 1px solid #e5e7eb; background: white; font-weight: 500; color: #374151; font-size: 11px; text-transform: uppercase; width: 100%; padding: 2px 4px; border-radius: 2px;">
                                                                            <option value="">SELECT</option>
                                                                            @if(!empty($field['options']) && is_array($field['options']))
                                                                                @foreach($field['options'] as $option)
                                                                                    <option value="{{ $option }}">{{ strtoupper($option) }}</option>
                                                                                @endforeach
                                                                            @endif
                                                                        </select>
                                            @else
                                                                        <input type="{{ ($field['type'] ?? '') == 'number' ? 'number' : 'text' }}"
                                                                               wire:model.live="test_results.{{ $testItem->id }}.{{ $field['name'] }}"
                                                                               style="border: 1px solid #e5e7eb; background: white; font-weight: 500; color: #374151; font-size: 11px; text-transform: uppercase; width: 100%; padding: 2px 4px; border-radius: 2px;"
                                                                               placeholder="ENTER VALUE">
                                            @endif
                                                                </div>
                                                            </div>
                                        </td>
                                                        @endforeach
                                    </tr>
                                @endforeach
                                                @else
                                                    <tr>
                                                        <td colspan="{{ $columnsPerRow }}" class="text-center text-muted" style="padding: 20px;">
                                                            No fields configured for this template
                                                        </td>
                                                    </tr>
                                                @endif
                            </tbody>
                        </table>
                    </div>
                @else
                                    <!-- Standard Table Template Display -->
                                    <div class="table-responsive">
                                        <table class="table table-bordered table-sm" style="font-size: 12px; border-collapse: collapse;">
                                            <thead>
                                                <tr style="background-color: #fef3c7;">
                                                    <th class="text-center" style="font-weight: bold; color: #92400e; font-size: 12px; padding: 8px 12px; border: 1px solid #ddd;">PARAMETER</th>
                                                    <th class="text-center" style="font-weight: bold; color: #92400e; font-size: 12px; padding: 8px 12px; border: 1px solid #ddd;">RESULT</th>
                                                    <th class="text-center" style="font-weight: bold; color: #92400e; font-size: 12px; padding: 8px 12px; border: 1px solid #ddd;">REFERENCE RANGE</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @if(!empty($formConfig['fields']))
                                                    @foreach($formConfig['fields'] as $fieldIndex => $field)
                                                    @php
                                                        $result = $testResults[$field['name']] ?? null;
                                                    @endphp
                                                    <tr style="background-color: {{ $fieldIndex % 2 == 0 ? '#fef3c7' : 'white' }};">
                                                        <td style="padding: 8px 12px; border: 1px solid #ddd; vertical-align: top; background-color: {{ $fieldIndex % 2 == 0 ? '#fef3c7' : 'white' }}; font-weight: 600; color: #374151; font-size: 11px; text-transform: uppercase;">
                                                            {{ strtoupper($field['label'] ?? 'PARAMETER') }}
                                                        </td>
                                                        <td style="padding: 8px 12px; border: 1px solid #ddd; vertical-align: top; background-color: {{ $fieldIndex % 2 == 0 ? '#fef3c7' : 'white' }};">
                                                            @if(($field['type'] ?? '') == 'dropdown')
                                                                <select wire:model.live="test_results.{{ $testItem->id }}.{{ $field['name'] }}" style="border: 1px solid #e5e7eb; background: white; font-weight: 500; color: #374151; font-size: 11px; text-transform: uppercase; width: 100%; padding: 2px 4px; border-radius: 2px;">
                                                                    <option value="">SELECT</option>
                                                                    @if(!empty($field['options']) && is_array($field['options']))
                                                                        @foreach($field['options'] as $option)
                                                                            <option value="{{ $option }}">{{ strtoupper($option) }}</option>
                                                                        @endforeach
                                                                    @endif
                                                                </select>
                                                            @else
                                                                <input type="{{ ($field['type'] ?? '') == 'number' ? 'number' : 'text' }}"
                                                                       wire:model.live="test_results.{{ $testItem->id }}.{{ $field['name'] }}"
                                                                       style="border: 1px solid #e5e7eb; background: white; font-weight: 500; color: #374151; font-size: 11px; text-transform: uppercase; width: 100%; padding: 2px 4px; border-radius: 2px;"
                                                                       placeholder="ENTER VALUE">
                                                            @endif
                                                        </td>
                                                        <td style="padding: 8px 12px; border: 1px solid #ddd; vertical-align: top; background-color: {{ $fieldIndex % 2 == 0 ? '#fef3c7' : 'white' }}; font-weight: 500; color: #6b7280; font-size: 11px;">
                                                            {{ strtoupper($field['reference_range'] ?? 'N/A') }}
                                                        </td>
                                                    </tr>
                                                    @endforeach
                                                @else
                                                    <tr>
                                                        <td colspan="3" class="text-center text-muted" style="padding: 20px;">
                                                            No fields configured for this template
                                                        </td>
                                                    </tr>
                                                @endif
                                            </tbody>
                                        </table>
                                    </div>
                                @endif
                            @else
                                <!-- No Form Configuration - Show Basic Results -->
                    <div class="alert alert-info">
                                    <i class="fas fa-info-circle me-2"></i>No form configuration found for this test template. Please configure the template first.
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
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" wire:click="$set('showResultsModal', false)">Cancel</button>
                <button type="button" class="btn btn-primary" wire:click="saveResults" wire:loading.attr="disabled">
                    <span wire:loading.remove wire:target="saveResults">
                        <i class="fas fa-save me-1"></i>Save Results
                    </span>
                    <span wire:loading wire:target="saveResults">
                        <i class="fas fa-spinner fa-spin me-1"></i>Saving...
                    </span>
                </button>
                <a href="{{ route('radiology.test.pdf', $selectedTest->id) }}" target="_blank" class="btn btn-success">
                    <i class="fas fa-print me-1"></i>Print Report
                </a>
            </div>
        </div>
    </div>
</div>
@endif
