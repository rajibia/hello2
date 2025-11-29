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
        <h4 class="mb-0">Laboratory Investigations</h4>
        @if(!$ipdId)
        <button wire:click="create" class="btn btn-primary">
            <i class="fas fa-plus me-2"></i>New Test Request
        </button>
        @endif
    </div>

    <!-- Incoming Request Modal (shown when a new request is created via OPD/IPD) -->
    @if($showNewRequestModal && $incomingTest)
    <div class="modal fade show" style="display: block;" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-warning">
                    <h5 class="modal-title fw-bold">New Lab Request Received</h5>
                    <button type="button" class="btn-close" wire:click="$set('showNewRequestModal', false)"></button>
                </div>
                <div class="modal-body">
                    <p class="mb-2">A new laboratory test request has been created.</p>
                    <ul class="list-unstyled">
                        <li><strong>Bill No:</strong> {{ $incomingTest->bill_no ?? 'N/A' }}</li>
                        <li><strong>Patient:</strong> {{ $incomingTest->patient->patientUser->full_name ?? 'N/A' }}</li>
                        <li><strong>Requested By:</strong> {{ $incomingTest->doctor->doctorUser->full_name ?? 'N/A' }}</li>
                        <li><strong>Tests:</strong>
                            @if($incomingTest->pathologyTestItems && $incomingTest->pathologyTestItems->count() > 0)
                                {{ $incomingTest->pathologyTestItems->pluck('pathologytesttemplate.test_name')->filter()->join(', ') }}
                            @else
                                N/A
                            @endif
                        </li>
                    </ul>
                    <div class="alert alert-info mt-3">
                        The person performing the test must first <strong>Accept</strong> this request before entering results.
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" wire:click="$set('showNewRequestModal', false)">Close</button>
                    <button type="button" class="btn btn-success" wire:click="acceptTest({{ $incomingTest->id }})">Accept & Start</button>
                </div>
            </div>
        </div>
    </div>
    @endif
    <!-- Search -->
    <div class="row mb-4">
        <div class="col-md-6">
            <div class="input-group">
                <input type="text" wire:model.debounce.300ms="search" class="form-control" placeholder="Search by bill number, patient name, or test name...">
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
                <table class="table table-hover border">
                    <thead class="table-primary"></thead>
                        <tr>
                            <th style="min-width: 100px; color: #1f2937; font-weight: 600; text-transform: uppercase; font-size: 12px;">Bill No</th>
                            <th style="min-width: 150px; color: #1f2937; font-weight: 600; text-transform: uppercase; font-size: 12px;">Patient</th>
                            <th style="min-width: 140px; color: #1f2937; font-weight: 600; text-transform: uppercase; font-size: 12px;">Test</th>
                            <th style="min-width: 200px; color: #1f2937; font-weight: 600; text-transform: uppercase; font-size: 12px;">Doctor</th>
                            <th style="min-width: 100px; color: #1f2937; font-weight: 600; text-transform: uppercase; font-size: 12px;">Status</th>
                            <th style="min-width: 120px; color: #1f2937; font-weight: 600; text-transform: uppercase; font-size: 12px;">Discount %</th>
                            <th style="min-width: 120px; color: #1f2937; font-weight: 600; text-transform: uppercase; font-size: 12px;">Amount</th>
                            <th style="min-width: 120px; color: #1f2937; font-weight: 600; text-transform: uppercase; font-size: 12px;">Amount Paid</th>
                            <th style="min-width: 120px; color: #1f2937; font-weight: 600; text-transform: uppercase; font-size: 12px;">Balance</th>
                            <th style="min-width: 120px; color: #1f2937; font-weight: 600; text-transform: uppercase; font-size: 12px;">Payment Status</th>
                            <th style="min-width: 120px; color: #1f2937; font-weight: 600; text-transform: uppercase; font-size: 12px;">Date</th>
                            <th style="min-width: 120px; color: #1f2937; font-weight: 600; text-transform: uppercase; font-size: 12px;">Actions</th>
                        </tr>
                    </thead>
                    </thead>
                    <tbody>
                        @forelse($tests as $test)
                        <tr>
                            <td>
                                <a href="{{ route('pathology.test.show', $test->id) }}" class="text-decoration-none">{{ $test->bill_no }}</a>
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
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div>
                                    @if($test->pathologyTestItems && $test->pathologyTestItems->count() > 0)
                                        <div class="fw-semibold">{{ $test->pathologyTestItems->count() }} Test(s)</div>
                                        <small class="text-muted">
                                            @foreach($test->pathologyTestItems->take(2) as $item)
                                                {{ $item->pathologytesttemplate->test_name ?? 'N/A' }}{{ !$loop->last ? ', ' : '' }}
                                            @endforeach
                                            @if($test->pathologyTestItems->count() > 2)
                                                +{{ $test->pathologyTestItems->count() - 2 }} more
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
                                    <span class="badge bg-success">Completed</span>
                                @else
                                    <span class="badge bg-secondary">Unknown</span>
                                @endif
                            </td>
                            <td>
                                @php
                                    $discAmt = (float)($test->discount ?? 0);
                                    $origTotal = (float)($test->total ?? 0);
                                    $discPct = $origTotal > 0 ? ($discAmt / $origTotal) * 100 : 0;
                                @endphp
                                @if($origTotal > 0 && $discAmt > 0)
                                    <span class="fw-semibold">GHS {{ number_format($discAmt, 2) }}</span>
                                    <small class="text-success ms-1">({{ number_format($discPct, 1) }}%)</small>
                                @else
                                    <span class="text-muted">GHS 0.00 (0%)</span>
                                @endif
                            </td>
                            <td>
                                @php
                                    $finalAmt = max(0, (float)($test->total ?? 0) - (float)($test->discount ?? 0));
                                @endphp
                                <span class="fw-semibold">GHS {{ number_format($finalAmt, 2) }}</span>
                            </td>
                            <td>
                                <span class="fw-semibold">GHS {{ number_format((float)($test->amount_paid ?? 0), 2) }}</span>
                            </td>
                            <td>
                                @php
                                    $original = (float)($test->total ?? 0);
                                    $disc = (float)($test->discount ?? 0);
                                    $paid = (float)($test->amount_paid ?? 0);
                                    $balance = max(0, round($original - $disc - $paid, 2));
                                @endphp
                                @if($balance > 0)
                                    <span class="text-danger fw-semibold">GHS {{ number_format($balance, 2) }}</span>
                                @else
                                    <span class="text-success fw-semibold">GHS {{ number_format($balance, 2) }}</span>
                                @endif
                            </td>
                            <td>
                                <div>
                                    @php
                                        $balance = (float)($test->balance ?? 0);
                                        $isPaid = $balance <= 0;
                                    @endphp
                                    @if($isPaid)
                                        <span class="badge bg-success">PAID</span>
                                    @else
                                        <span class="badge bg-danger">UNPAID</span>
                                    @endif
                                </div>
                            </td>
                            <td>
                                <div>
                                    <div class="fw-semibold">{{ $test->created_at->format('M d, Y') }}</div>
                                    <small class="text-muted">{{ $test->created_at->format('h:i A') }}</small>
                                </div>
                            </td>
                            <td>
                                <div class="d-flex gap-1">
                                    @php
                                        $balance = (float)($test->balance ?? 0);
                                        $isPaid = $balance <= 0;
                                        $isCompleted = $test->status == 1;
                                        $isCompanyPatient = $test->patient && $test->patient->company_id;
                                        $canViewOrEdit = $isPaid || $isCompanyPatient;
                                    @endphp

                                    @if($canViewOrEdit && !$ipdId)
                                        <button wire:click="viewResults({{ $test->id }})" class="btn btn-sm btn-primary" title="View Results">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                    @elseif(!$canViewOrEdit && !$ipdId)
                                        <button class="btn btn-sm btn-secondary" disabled title="Bill not paid - Outstanding: GHS {{ number_format($balance, 2) }}">
                                            <i class="fas fa-lock"></i>
                                        </button>
                                    @endif

                                    @if(!$isPaid && !$ipdId)
                                        <button wire:click="edit({{ $test->id }})" class="btn btn-sm btn-info" title="Edit Test">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                    @endif

                                    @if(!$ipdId)
                                    <button wire:click="delete({{ $test->id }})" class="btn btn-sm btn-danger" title="Delete Test" onclick="return confirm('Are you sure you want to delete this test request?')">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                    @endif

                                    <!-- Payment Icon - Only show for individual patients with balance -->
                                    @if($balance > 0 && !$ipdId && !$isCompanyPatient)
                                            <a target="_blank" href="{{ route('patient.bills.show', $test->patient_id) }}" title="View Patient Bills" class="btn btn-sm btn-warning">
                                                <i class="fa fa-credit-card"></i>
                                            </a>
                                        @endif

                                    <!-- Company Bills Icon - Only show for company patients with balance -->
                                    @if($balance > 0 && !$ipdId && $isCompanyPatient)
                                        <a target="_blank" href="{{ route('companies.view', $test->patient->company_id) }}" title="View Company Bills" class="btn btn-sm btn-warning">
                                            <i class="fa fa-building"></i>
                                        </a>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="12" class="text-center py-4">
                                <div class="text-muted">
                                    <i class="fas fa-inbox fa-3x mb-3"></i>
                                    <p>No pathology tests found.</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="d-flex justify-content-center">
                {{ $tests->links('vendor.pagination.livewire-bootstrap-4') }}
            </div>
        </div>
    </div>

    <!-- Create Modal -->
    @if($showCreateModal)
    <div class="modal fade show" style="display: block;" tabindex="-1">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header bg-light">
                    <h5 class="modal-title fw-bold">
                        <i class="fas fa-plus me-2 text-success"></i>Create New Pathology Test Request
                    </h5>
                    <button type="button" class="btn-close" wire:click="closeCreateModal"></button>
                </div>
                <div class="modal-body p-4">
                    <!-- Basic Information Section -->
                    <div class="card mb-4">
                        <div class="card-header bg-primary text-white">
                            <h6 class="mb-0 fw-semibold">
                                <i class="fas fa-info-circle me-2"></i>Basic Information
                            </h6>
                        </div>
                        <div class="card-body">
                            <div class="row g-3">
                        <!-- Patient Selection -->
                        <div class="col-md-6">
                                    <label class="form-label fw-semibold text-dark">Patient <span class="text-danger">*</span></label>
                                    <select wire:model="patient_id" class="form-select border-2" wire:change="loadPatientCases">
                                <option value="">Select Patient</option>
                                @foreach($patients as $id => $name)
                                    <option value="{{ $id }}">{{ $name }}</option>
                                @endforeach
                            </select>
                                    @error('patient_id') <span class="text-danger small fw-semibold">{{ $message }}</span> @enderror
                        </div>

                        <!-- Case ID -->
                        <div class="col-md-6">
                                    <label class="form-label fw-semibold text-dark">Case ID <span class="text-danger">*</span></label>
                                    <select wire:model="case_id" class="form-select border-2" {{ empty($available_cases) ? 'disabled' : '' }}>
                                <option value="">Select Case ID</option>
                                @foreach($available_cases as $id => $case_id)
                                    <option value="{{ $id }}">{{ $case_id }}</option>
                                @endforeach
                            </select>
                            @if(empty($available_cases) && $patient_id)
                                        <small class="text-warning fw-semibold">
                                    <i class="fas fa-exclamation-triangle me-1"></i>No cases found for this patient
                                </small>
                            @endif
                                    @error('case_id') <span class="text-danger small fw-semibold">{{ $message }}</span> @enderror
                        </div>

                        <!-- Doctor Selection -->
                        <div class="col-md-6">
                                    <label class="form-label fw-semibold text-dark">Referral Doctor <span class="text-danger">*</span></label>
                                    <select wire:model="doctor_id" class="form-select border-2">
                                <option value="">Select Doctor</option>
                                @foreach($doctors as $id => $name)
                                    <option value="{{ $id }}">{{ $name }}</option>
                                @endforeach
                            </select>
                                    @error('doctor_id') <span class="text-danger small fw-semibold">{{ $message }}</span> @enderror
                        </div>

                        <!-- Notes -->
                        <div class="col-12">
                                    <label class="form-label fw-semibold text-dark">Notes</label>
                                    <textarea wire:model="note" class="form-control border-2" rows="3" placeholder="Enter any additional notes or special instructions..."></textarea>
                                    @error('note') <span class="text-danger small fw-semibold">{{ $message }}</span> @enderror
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Test Requests Section -->
                    <div class="card">
                        <div class="card-header bg-success text-white d-flex justify-content-between align-items-center">
                            <h6 class="mb-0 fw-semibold">
                                <i class="fas fa-flask me-2"></i>Test Requests
                            </h6>
                            <button type="button" wire:click="addTest" class="btn btn-light btn-sm fw-semibold">
                                <i class="fas fa-plus me-1"></i>Add Test
                            </button>
                        </div>
                        <div class="card-body p-0">
                        @if(count($selected_tests) > 0)
                            <div class="table-responsive">
                                    <table class="table table-hover mb-0">
                                        <thead class="table-dark">
                                            <tr>
                                                <th class="text-white fw-semibold" style="width: 35%; min-width: 200px;">Test Name <span class="text-danger">*</span></th>
                                                <th class="text-white fw-semibold text-center" style="width: 12%; min-width: 100px;">Report Days</th>
                                                <th class="text-white fw-semibold text-center" style="width: 18%; min-width: 140px;">Report Date</th>
                                                <th class="text-white fw-semibold text-center" style="width: 15%; min-width: 120px;">Amount (GHS)</th>
                                                <th class="text-white fw-semibold text-center" style="width: 10%; min-width: 80px;">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($selected_tests as $testId => $testData)
                                                <tr class="align-middle">
                                                    <td class="p-2">
                                                        <select wire:model="selected_tests.{{ $testId }}.template_id" class="form-select border-2">
                                                            <option value="">Select Test Template</option>
                                                        @foreach($available_templates as $template)
                                                            <option value="{{ $template->id }}">
                                                                    {{ $template->test_name }}
                                                                    @if($template->short_name)
                                                                        <span class="text-muted">({{ $template->short_name }})</span>
                                                                    @endif
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </td>
                                                    <td class="p-2 text-center">
                                                        <input type="number" wire:model="selected_tests.{{ $testId }}.report_days" class="form-control text-center border-2" placeholder="Days" readonly style="background-color: #f8f9fa; cursor: not-allowed;" title="Auto-calculated based on selected test template">
                                                </td>
                                                    <td class="p-2 text-center">
                                                        <input type="date" wire:model="selected_tests.{{ $testId }}.report_date" class="form-control text-center border-2" readonly style="background-color: #f8f9fa; cursor: not-allowed;" title="Auto-calculated based on selected test template">
                                                </td>
                                                    <td class="p-2 text-center">
                                                        <input type="number" wire:model="selected_tests.{{ $testId }}.amount" class="form-control text-center border-2" placeholder="0.00" step="0.01" readonly style="background-color: #f8f9fa; cursor: not-allowed;" title="Auto-filled from test template standard charge">
                                                </td>
                                                    <td class="p-2 text-center">
                                                        <button type="button" wire:click="removeTest({{ $testId }})" class="btn btn-outline-danger btn-sm" title="Remove Test">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                                <div class="text-center py-5 text-muted">
                                    <i class="fas fa-flask fa-3x mb-3 text-muted"></i>
                                    <h6 class="text-muted">No Test Items Added</h6>
                                    <p class="text-muted mb-0">Click "Add Test" to start adding pathology test requests.</p>
                            </div>
                        @endif
                    </div>
                </div>
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-secondary fw-semibold" wire:click="closeCreateModal">
                        <i class="fas fa-times me-1"></i>Cancel
                    </button>
                    <button type="button" class="btn btn-primary fw-semibold" wire:click="store" {{ $isSubmitting ? 'disabled' : '' }}>
                        @if($isSubmitting)
                            <i class="fas fa-spinner fa-spin me-2"></i>Creating Request...
                        @else
                            <i class="fas fa-plus me-1"></i>Create Test Request
                        @endif
                    </button>
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- Edit Modal -->
    @if($showEditModal)
    <div class="modal fade show" style="display: block;" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="fas fa-edit me-2 text-primary"></i>Edit Pathology Test
                    </h5>
                    <button type="button" class="btn-close" wire:click="closeEditModal"></button>
                </div>
                <div class="modal-body">
                    <div class="row g-3 mb-4">
                            <!-- Patient Selection -->
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Patient <span class="text-danger">*</span></label>
                            <select wire:model="patient_id" class="form-select" wire:change="loadPatientCases">
                                    <option value="">Select Patient</option>
                                    @foreach($patients as $id => $name)
                                        <option value="{{ $id }}">{{ $name }}</option>
                                    @endforeach
                                </select>
                                @error('patient_id') <span class="text-danger small">{{ $message }}</span> @enderror
                            </div>

                            <!-- Doctor Selection -->
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Doctor <span class="text-danger">*</span></label>
                                <select wire:model="doctor_id" class="form-select">
                                    <option value="">Select Doctor</option>
                                    @foreach($doctors as $id => $name)
                                        <option value="{{ $id }}">{{ $name }}</option>
                                    @endforeach
                                </select>
                                @error('doctor_id') <span class="text-danger small">{{ $message }}</span> @enderror
                            </div>

                            <!-- Case ID -->
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Case ID <span class="text-danger">*</span></label>
                            <select wire:model="case_id" class="form-select" {{ empty($available_cases) ? 'disabled' : '' }}>
                                    <option value="">Select Case</option>
                                    @foreach($available_cases as $id => $case)
                                        <option value="{{ $id }}">{{ $case }}</option>
                                    @endforeach
                                </select>
                            @if(empty($available_cases) && $patient_id)
                                <small class="text-warning">
                                    <i class="fas fa-exclamation-triangle me-1"></i>No cases found for this patient
                                    </small>
                                @endif
                            @error('case_id') <span class="text-danger small">{{ $message }}</span> @enderror
                            </div>

                            <!-- Notes -->
                            <div class="col-12">
                                <label class="form-label fw-semibold">Notes</label>
                            <textarea wire:model="note" class="form-control" rows="2" placeholder="Enter any additional notes..."></textarea>
                                @error('note') <span class="text-danger small">{{ $message }}</span> @enderror
                            </div>
                        </div>

                    <!-- Multiple Tests Section -->
                    <div class="mt-4">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h6 class="mb-0 fw-semibold">Test Items</h6>
                            <button type="button" wire:click="addEditTest" class="btn btn-success btn-sm">
                                <i class="fas fa-plus me-1"></i>Add Test
                            </button>
                        </div>

                        @if(count($edit_selected_tests) > 0)
                            <div class="table-responsive">
                                <table class="table table-bordered table-sm">
                                    <thead class="table-light">
                                        <tr>
                                            <th style="width: 17%">Test Name <span class="text-danger">*</span></th>
                                            <th style="width: 15%">Report Days</th>
                                            <th style="width: 10%">Report Date</th>
                                            <th style="width: 15%">Amount (GHS)</th>
                                            <th style="width: 5%">Action</th>
                                        </tr>
                                        <tr>
                                            <td colspan="5" class="text-muted small">
                                                <i class="fas fa-info-circle me-1"></i>Report Days, Report Date, and Amount are auto-filled based on the selected test template.
                                            </td>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($edit_selected_tests as $testId => $testData)
                                            <tr>
                                                <td>
                                                    <select wire:model="edit_selected_tests.{{ $testId }}.template_id" class="form-select form-select-sm">
                                                        <option value="">Select Test Name</option>
                                                        @foreach($available_templates as $template)
                                                            <option value="{{ $template->id }}">
                                                                {{ $template->test_name }} ({{ $template->short_name }})
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </td>
                                                <td>
                                                    <input type="text" value="{{ $testData['report_days'] ? $testData['report_days'] . ' days' : '' }}" class="form-control form-control-sm" readonly style="background-color: #f8f9fa; color: #6c757d; cursor: not-allowed;" title="Auto-filled from test template">
                                                </td>
                                                <td>
                                                    <input type="text" value="{{ $testData['report_date'] ?? '' }}" class="form-control form-control-sm" readonly style="background-color: #f8f9fa; color: #6c757d; cursor: not-allowed;" title="Auto-calculated from report days">
                                                </td>
                                                <td>
                                                    <input type="text" value="{{ $testData['amount'] && is_numeric($testData['amount']) ? 'GHS ' . number_format((float)$testData['amount'], 2) : '' }}" class="form-control form-control-sm" readonly style="background-color: #f8f9fa; color: #6c757d; cursor: not-allowed;" title="Auto-filled from test template">
                                                </td>
                                                <td>
                                                    <button type="button" wire:click="removeEditTest({{ $testId }})" class="btn btn-danger btn-sm">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="text-center py-4 text-muted">
                                <i class="fas fa-flask fa-2x mb-2"></i>
                                <p>No test items found. Loading existing tests...</p>
                            </div>
                        @endif
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" wire:click="closeEditModal">
                        <i class="fas fa-times me-1"></i>Cancel
                    </button>
                    <button type="button" class="btn btn-primary" wire:click="update" wire:loading.attr="disabled" {{ $isSubmitting ? 'disabled' : '' }}>
                        @if($isSubmitting)
                            <i class="fas fa-spinner fa-spin me-2"></i>Updating...
                        @else
                            <i class="fas fa-save me-1"></i>Update Test
                        @endif
                    </button>
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- Results Modal -->
    @if($showResultsModal && $selectedTest)
    <div class="modal fade show pathology-report-modal" style="display: block;" tabindex="-1">
        <div class="modal-dialog modal-xl">
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
                            LABORATORY RESULTS
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
                                <span class="pathology-admin-label" style="font-weight: 600; color: #92400e; font-size: 12px; text-transform: uppercase;">SPECIMEN : {{ strtoupper($selectedTest->pathologyTestItems->first()->pathologytesttemplate->test_type ?? 'BLOOD') }}</span>
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
                                    @if($selectedTest->pathologyTestItems && $selectedTest->pathologyTestItems->count() > 0)
                                        @php
                                            $testNames = $selectedTest->pathologyTestItems->pluck('pathologytesttemplate.test_name')->filter()->toArray();
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
                    @if($selectedTest->pathologyTestItems && $selectedTest->pathologyTestItems->count() > 0)
                        @foreach($selectedTest->pathologyTestItems as $index => $testItem)
                            @php
                                $template = $testItem->pathologytesttemplate;
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
                                                                                   style="border: 1px solid #e5e7eb; background: white; font-weight: 500; color: #374151; font-size: 11px; width: 100%; padding: 2px 4px; border-radius: 2px;"
                                                                                   placeholder="Enter value"
                                                                                   step="{{ ($field['type'] ?? '') == 'number' ? '0.01' : '1' }}">
                                                                        @endif
                                                                    </div>
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

                                            // Get existing values for this test item
                                            $existingResults = $test_results[$testItem->id] ?? [];
                                            $currentResults = $existingResults['results'] ?? '';
                                            $currentSpecies = $existingResults['species'] ?? '';
                                            $currentStage = $existingResults['stage'] ?? '';
                                            $currentCount = $existingResults['count'] ?? '';
                                            $currentUnit = $existingResults['unit'] ?? '';

                                            // Debug information
                                            $debugResults = $this->resultsOptions[$testItem->id] ?? [];
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
                                                        <td style="text-align: center;">
                                                            <select wire:model.live="test_results.{{ $testItem->id }}.results"
                                                                    wire:change="clearSpeciesAndStage({{ $testItem->id }})"
                                                                    style="border: none; background: transparent; font-weight: 500; color: #374151; font-size: 12px; text-transform: uppercase; width: 100%; text-align: center;">
                                                                <option value="">SELECT</option>
                                                                @foreach($this->resultsOptions[$testItem->id] ?? [] as $option)
                                                                    <option value="{{ $option }}" {{ $currentResults == $option ? 'selected' : '' }}>{{ strtoupper($option) }}</option>
                                                                @endforeach
                                                            </select>
                                                        </td>
                                                        <td style="text-align: center;">
                                                            <select wire:model.live="test_results.{{ $testItem->id }}.species"
                                                                    wire:change="clearStage({{ $testItem->id }})"
                                                                    style="border: none; background: transparent; font-weight: 500; color: #374151; font-size: 12px; text-transform: uppercase; width: 100%; text-align: center;">
                                                                <option value="">SELECT</option>
                                                                @foreach($this->speciesOptions[$testItem->id] ?? ['N/A'] as $option)
                                                                    <option value="{{ $option }}" {{ $currentSpecies == $option ? 'selected' : '' }}>{{ strtoupper($option) }}</option>
                                                                @endforeach
                                                            </select>
                                                        </td>
                                                        <td style="text-align: center;">
                                                            <select wire:model.live="test_results.{{ $testItem->id }}.stage" style="border: none; background: transparent; font-weight: 500; color: #374151; font-size: 12px; text-transform: uppercase; width: 100%; text-align: center;">
                                                                <option value="">SELECT</option>
                                                                @foreach($this->stageOptions[$testItem->id] ?? ['N/A'] as $option)
                                                                    <option value="{{ $option }}" {{ $currentStage == $option ? 'selected' : '' }}>{{ strtoupper($option) }}</option>
                                                                @endforeach
                                                            </select>
                                                        </td>
                                                        <td style="text-align: center;">
                                                            <input type="text"
                                                                   wire:model.live="test_results.{{ $testItem->id }}.count"
                                                                   value="{{ $currentCount }}"
                                                                   style="border: none; background: transparent; font-weight: 500; color: #374151; font-size: 12px; text-align: center; width: 100%;"
                                                                   placeholder="Enter count">
                                                        </td>
                                                        <td style="text-align: center;">
                                                            <select wire:model.live="test_results.{{ $testItem->id }}.unit" style="border: none; background: transparent; font-weight: 500; color: #374151; font-size: 12px; text-transform: uppercase; width: 100%; text-align: center;">
                                                                <option value="">SELECT</option>
                                                                @php
                                                                    $units = $speciesConfig['units'] ?? '';
                                                                    $unitOptions = [];
                                                                    if (is_array($units)) {
                                                                        $unitOptions = $units;
                                                                    } elseif (is_string($units) && !empty($units)) {
                                                                        $unitOptions = array_map('trim', explode(',', $units));
                                                                    }
                                                                @endphp
                                                                @foreach($unitOptions as $option)
                                                                    <option value="{{ $option }}" {{ $currentUnit == $option ? 'selected' : '' }}>{{ strtoupper($option) }}</option>
                                                                @endforeach
                                                            </select>
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
                                                            $result = $test_results[$testItem->id][$field['name']] ?? null;
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
                                                            <td style="text-align: center;">
                                                                @if(($field['type'] ?? '') == 'dropdown')
                                                                    <select wire:model.live="test_results.{{ $testItem->id }}.{{ $field['name'] }}" style="border: none; background: transparent; font-weight: 500; color: #374151; font-size: 12px; text-transform: uppercase; width: 100%; text-align: center;">
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
                                                                           style="border: none; background: transparent; font-weight: 500; color: #374151; font-size: 12px; text-align: center; width: 100%;"
                                                                           placeholder="0"
                                                                           step="{{ ($field['type'] ?? '') == 'number' ? '0.01' : '1' }}">
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
                                                            <td style="text-align: center;">
                                                                @if(($field['type'] ?? '') == 'dropdown')
                                                                    <select wire:model.live="test_results.{{ $testItem->id }}.{{ $field['name'] }}" style="border: none; background: transparent; font-weight: 500; color: #374151; font-size: 12px; text-transform: uppercase; width: 100%; text-align: center;">
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
                                                                           style="border: none; background: transparent; font-weight: 500; color: #374151; font-size: 12px; text-align: center; width: 100%;"
                                                                           placeholder="0"
                                                                           step="{{ ($field['type'] ?? '') == 'number' ? '0.01' : '1' }}">
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
                                                                    $result = $test_results[$testItem->id][$field['name']] ?? null;
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
                                                                <td style="text-align: center;">
                                                                    @if(($field['type'] ?? '') == 'dropdown')
                                                                        <select wire:model.live="test_results.{{ $testItem->id }}.{{ $field['name'] }}" style="border: none; background: transparent; font-weight: 500; color: #374151; font-size: 11px; text-transform: uppercase; width: 100%; text-align: center;">
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
                                                                               style="border: none; background: transparent; font-weight: 500; color: #374151; font-size: 11px; text-align: center; width: 100%;"
                                                                               placeholder="0"
                                                                               step="{{ ($field['type'] ?? '') == 'number' ? '0.01' : '1' }}">
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
                                            $result = $test_results[$testItem->id][$field['name']] ?? null;
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
                                                    <td style="text-align: center;">
                                                        @if(($field['type'] ?? '') == 'dropdown')
                                                            <select wire:model.live="test_results.{{ $testItem->id }}.{{ $field['name'] }}" style="border: none; background: transparent; font-weight: 500; color: #374151; font-size: 12px; text-transform: uppercase; width: 100%; text-align: center;">
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
                                                                   style="border: none; background: transparent; font-weight: 500; color: #374151; font-size: 12px; text-align: center; width: 100%;"
                                                                   placeholder="0"
                                                                   step="{{ ($field['type'] ?? '') == 'number' ? '0.01' : '1' }}">
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
                    <div class="signature-section mt-4">
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
                <div class="modal-footer pathology-report-footer">
                    <button type="button" class="btn btn-secondary" wire:click="$set('showResultsModal', false)">Cancel</button>
                    <button type="button" class="btn btn-success" wire:click="updateResults" @if($isSubmitting) disabled @endif>
                        @if($isSubmitting)
                            <i class="fas fa-spinner fa-spin me-2"></i>Saving...
                        @else
                            <i class="fas fa-save me-2"></i>Save Results
                        @endif
                    </button>
                </div>
            </div>
        </div>
    </div>
    @endif
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

/* Input field styling for editable results */
input[type="text"], input[type="number"], select {
    border: 1px solid #e5e7eb !important;
    border-radius: 4px !important;
    padding: 4px 8px !important;
    font-size: 12px !important;
    transition: border-color 0.2s ease !important;
}

input[type="text"]:focus, input[type="number"]:focus, select:focus {
    border-color: #3b82f6 !important;
    outline: none !important;
    box-shadow: 0 0 0 2px rgba(59, 130, 246, 0.1) !important;
}

select {
    background-color: white !important;
    cursor: pointer !important;
}

/* Pathology report specific input styling */
.pathology-report-container input[type="text"],
.pathology-report-container input[type="number"],
.pathology-report-container select {
    border: none !important;
    background: transparent !important;
    font-weight: 500 !important;
    color: #374151 !important;
    font-size: 12px !important;
    text-align: center !important;
    width: 100% !important;
    padding: 2px 4px !important;
}

.pathology-report-container input[type="text"]:focus,
.pathology-report-container input[type="number"]:focus,
.pathology-report-container select:focus {
    border: 1px solid #3b82f6 !important;
    background: rgba(59, 130, 246, 0.05) !important;
    outline: none !important;
}

.pathology-report-container select {
    cursor: pointer !important;
    background: transparent !important;
}

.pathology-report-container select option {
    background: white !important;
    color: #374151 !important;
}

/* Header input field styling */
.pathology-admin-details input[type="text"] {
    border: 1px solid transparent !important;
    background: transparent !important;
    font-weight: 600 !important;
    font-size: 12px !important;
    text-transform: uppercase !important;
    transition: border-color 0.2s ease !important;
    padding: 2px 4px !important;
    border-radius: 2px !important;
}

.pathology-admin-details input[type="text"]:focus {
    border-color: #3b82f6 !important;
    background: rgba(59, 130, 246, 0.05) !important;
    outline: none !important;
}

.pathology-admin-details input[type="text"]::placeholder {
    color: #9ca3af !important;
    font-weight: 400 !important;
    text-transform: none !important;
}

/* Field-Value Multi-Column template input styling */
.field-value-pair input[type="text"],
.field-value-pair input[type="number"],
.field-value-pair select {
    border: 1px solid #e5e7eb !important;
    border-radius: 4px !important;
    padding: 4px 8px !important;
    font-size: 11px !important;
    transition: border-color 0.2s ease !important;
    background: white !important;
}

.field-value-pair input[type="text"]:focus,
.field-value-pair input[type="number"]:focus,
.field-value-pair select:focus {
    border-color: #3b82f6 !important;
    outline: none !important;
    box-shadow: 0 0 0 2px rgba(59, 130, 246, 0.1) !important;
}

.field-value-pair select {
    cursor: pointer !important;
}

.field-value-pair select option {
    background: white !important;
    color: #374151 !important;
    font-size: 11px !important;
}

/* Signature section styling */
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
</style>

<script>
document.addEventListener('livewire:load', function () {
    // Handle real-time updates for test results
    Livewire.on('testResultsUpdated', function() {
        console.log('Test results updated - flags recalculated');
    });

    // Add event listeners for input fields to trigger flag updates
    document.addEventListener('input', function(e) {
        if (e.target.matches('input[wire\\:model\\.live*="test_results"], select[wire\\:model\\.live*="test_results"]')) {
            // Livewire will automatically update the flags when test_results changes
            // No need for manual method calls
            console.log('Input field updated - flags should update automatically');
        }
    });
});

// Simple and reliable print function
function printLaboratoryReport() {
    const reportContent = document.getElementById('pathology-report-content');
    if (!reportContent) {
        alert('Report content not found.');
        return;
    }

    const originalTitle = document.title;
    document.title = 'Laboratory Test Report - {{ $selectedTest->lab_number ?? $selectedTest->bill_no ?? "Report" }}';

    const printHTML = `
        <html>
        <head>
            <title>${document.title}</title>
            <style>
                * {
                    -webkit-print-color-adjust: exact !important;
                    color-adjust: exact !important;
                    print-color-adjust: exact !important;
                }

                body {
                    margin: 0 !important;
                    padding: 20px !important;
                    background: white !important;
                    font-size: 12px !important;
                    line-height: 1.4 !important;
                    font-family: Arial, sans-serif !important;
                }

                .pathology-report-container {
                    background: white !important;
                    margin: 0 !important;
                    padding: 0 !important;
                    box-shadow: none !important;
                    border: none !important;
                }

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

                .pathology-admin-details .row:first-child {
                    background: #fbbf24 !important;
                    background-color: #fbbf24 !important;
                    padding: 8px 12px !important;
                    border-radius: 4px !important;
                    margin: 0 !important;
                }

                .pathology-admin-details .row:not(:first-child) {
                    background: white !important;
                    background-color: white !important;
                    padding: 8px 12px !important;
                    border-radius: 4px !important;
                    border: 1px solid #e5e7eb !important;
                    margin: 0 !important;
                }

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

                .pathology-results-header[style*="background: #fff1cc"] {
                    background: #fff1cc !important;
                    background-color: #fff1cc !important;
                }

                .pathology-results-header[style*="background: #fff1cc"] h6 {
                    color: #1e40af !important;
                }

                .pathology-results-table {
                    width: 100% !important;
                    border-collapse: collapse !important;
                    margin-bottom: 15px !important;
                }

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

                .pathology-results-table tbody td {
                    border: 1px solid #e5e7eb !important;
                    padding: 8px 12px !important;
                    text-align: left !important;
                    vertical-align: middle !important;
                }

                .pathology-results-table tbody tr:nth-child(even) {
                    background: #f8f9fa !important;
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

                .no-print {
                    display: none !important;
                }

                @media print {
                    body { margin: 0 !important; }
                    .no-print { display: none !important; }
                }
            </style>
        </head>
        <body>
            ${reportContent.innerHTML}
        </body>
        </html>
    `;

    const printWindow = window.open('', '_blank');
    printWindow.document.write(printHTML);
    printWindow.document.close();
        printWindow.focus();
        printWindow.print();
            printWindow.close();
            document.title = originalTitle;
}

// Debug Livewire functionality
document.addEventListener('livewire:load', function () {
    console.log('Livewire loaded successfully for pathology-test-table');

    // Check if Livewire is working
    if (typeof Livewire !== 'undefined') {
        console.log('Livewire object is available');
    } else {
        console.error('Livewire object is not available');
    }
});

// Error handling for Livewire
document.addEventListener('livewire:error', function (event) {
    console.error('Livewire error:', event.detail);
});

// Check for JavaScript errors
window.addEventListener('error', function (event) {
    console.error('JavaScript error:', event.error);
});
</script>

