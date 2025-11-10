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
        <h4 class="mb-0">Pathology Tests</h4>
            @if($ipdId)
                <small class="text-muted">Showing all pathology tests for this IPD patient ({{ $tests->count() }} found)</small>
                <!-- DEBUG: Actual count is {{ $tests->count() }} -->
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
                                <a href="{{ route('pathology.test.show', $test->id) }}" class="fw-semibold text-primary text-decoration-none">
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
                                    @if($test->pathologyTestItems && $test->pathologyTestItems->count() > 0)
                                        <div class="fw-semibold">{{ $test->pathologyTestItems->count() }} Test(s)</div>
                                        <small class="text-muted">
                                            @foreach($test->pathologyTestItems->take(2) as $item)
                                                @php
                                                    $template = $item->pathologytesttemplate;
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
                                <div class="d-flex justify-content-center align-items-center mt-2">
                                    @if($test->status == 2)
                                        <span class="badge bg-light-success">Done</span>
                                    @elseif($test->status == 1)
                                        <span class="badge bg-light-warning">In Progress</span>
                                    @else
                                        <span class="badge bg-light-info">Pending</span>
                                    @endif
                                </div>
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
                                    @if(!$ipdId)
                                    <!-- Preview Button -->
                                    <a href="javascript:void(0)" title="View Pathology Test" class="showPathologyTestBillBtn btn btn-sm btn-outline-success" data-id="{{ $test->id }}">
                                        <i class="fas fa-eye"></i>
                                    </a>

                                    <!-- Print Button -->
                                    <a href="{{ route('pathology.test.pdf', $test->id) }}" title="Print Pathology Test" class="btn btn-sm btn-outline-warning" target="_blank">
                                        <i class="fas fa-print"></i>
                                    </a>

                                    <!-- Accept Button (for Lab Technicians) -->
                                    @if($test->status == 0 && (Auth::user()->hasRole('Lab Technician') || Auth::user()->hasRole('Admin')))
                                        <form action="{{ route('pathology.test.accept', $test->id) }}" method="POST" style="display: inline;">
                                            @csrf
                                            <button type="submit" title="Accept Test Request" class="btn btn-sm btn-outline-info"
                                                    onclick="return confirm('Are you sure you want to accept this test request?')">
                                                <i class="fas fa-check"></i>
                                            </button>
                                        </form>
                                    @endif

                                    <!-- Edit Button -->
                                    @if((!str_contains(request()->route()->getName(), "ipd") && !str_contains(request()->route()->getName(), "opd")))
                                        <a href="{{ route('pathology.test.edit', $test->id) }}" title="Edit Pathology Test" class="btn btn-sm btn-outline-primary">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                    @else
                                        <a href="javascript:void(0)" title="View Pathology Test" class="editPathologyTestBillBtn btn btn-sm btn-outline-primary" data-id="{{ $test->id }}">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                    @endif

                                    <!-- Delete Button -->
                                    <a href="javascript:void(0)" title="Delete Pathology Test" data-id="{{ $test->id }}"
                                       class="deletePathologyTestBtn btn btn-sm btn-outline-danger">
                                        <i class="fas fa-trash"></i>
                                    </a>
                                    @else
                                        <!-- In IPD context, show only basic info -->
                                        <span class="text-muted small">View only</span>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="11" class="text-center">
                                <div class="py-4">
                                    <i class="fas fa-flask fa-3x text-muted mb-3"></i>
                                    <h5 class="text-muted">No Pathology Tests Found</h5>
                                    @if($ipdId)
                                        <p class="text-muted">No pathology tests found for this IPD patient.</p>
                                    @else
                                    <p class="text-muted">No pathology tests have been created yet.</p>
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
</div>
