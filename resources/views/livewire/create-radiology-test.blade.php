<div>
    <!-- Error Messages -->
    @if (session()->has('error'))
        <div class="alert alert-danger alert-dismissible fade show mb-3" role="alert">
            <i class="fas fa-exclamation-triangle me-2"></i>
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <!-- Success Messages -->
    @if (session()->has('success'))
        <div class="alert alert-success alert-dismissible fade show mb-3" role="alert">
            <i class="fas fa-check-circle me-2"></i>
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <form wire:submit.prevent="store">
        <div class="row g-3">
            <!-- Patient and Case Information -->
            <div class="row">
                @if ($patient_id != '')
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">{{ __('messages.prescription.patient') }} <span class="text-danger">*</span></label>
                        <select wire:model="patient_id" class="form-select" {{ $patient_id != '' ? 'disabled' : '' }} required>
                            <option value="">Select Patient</option>
                            @foreach($patients as $id => $name)
                                <option value="{{ $id }}" {{ $patient_id == $id ? 'selected' : '' }}>{{ $name }}</option>
                            @endforeach
                        </select>
                        @if($patient_id != '')
                            <input type="hidden" wire:model="patient_id" value="{{ $patient_id }}">
                        @endif
                    </div>
                @else
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">{{ __('messages.prescription.patient') }} <span class="text-danger">*</span></label>
                        <select wire:model="patient_id" class="form-select" required>
                            <option value="">Select Patient</option>
                            @foreach($patients as $id => $name)
                                <option value="{{ $id }}">{{ $name }}</option>
                            @endforeach
                        </select>
                    </div>
                @endif

                @if ($opd_id != '')
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">{{ __('messages.opd_patient.opd_number') }}</label>
                        <select wire:model="opd_id" class="form-select" {{ $opd_id != '' ? 'disabled' : '' }}>
                            <option value="">Select OPD</option>
                            @foreach($opds as $id => $number)
                                <option value="{{ $id }}" {{ $opd_id == $id ? 'selected' : '' }}>{{ $number }}</option>
                            @endforeach
                        </select>
                        @if($opd_id != '')
                            <input type="hidden" wire:model="opd_id" value="{{ $opd_id }}">
                        @endif
                    </div>
                @endif

                @if ($ipd_id != '')
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">{{ __('messages.ipd_patient.ipd_number') }}</label>
                        <select wire:model="ipd_id" class="form-select" {{ $ipd_id != '' ? 'disabled' : '' }}>
                            <option value="">Select IPD</option>
                            @foreach($ipds as $id => $number)
                                <option value="{{ $id }}" {{ $ipd_id == $id ? 'selected' : '' }}>{{ $number }}</option>
                            @endforeach
                        </select>
                        @if($ipd_id != '')
                            <input type="hidden" wire:model="ipd_id" value="{{ $ipd_id }}">
                        @endif
                    </div>
                @endif

                <div class="col-md-6">
                    <label class="form-label fw-semibold">{{ __('messages.ipd_patient.case_id') }} <span class="text-danger">*</span></label>
                    <select wire:model="case_id" class="form-select" {{ $case_id != '' ? 'disabled' : '' }} required>
                        <option value="">Select Case</option>
                        @foreach($caseIds as $id => $caseId)
                            <option value="{{ $id }}" {{ $case_id == $id ? 'selected' : '' }}>{{ $caseId }}</option>
                        @endforeach
                    </select>
                    @if($case_id != '')
                        <input type="hidden" wire:model="case_id" value="{{ $case_id }}">
                    @endif
                </div>

                <div class="col-md-6">
                    <label class="form-label fw-semibold">REFERRAL DOCTOR <span class="text-danger">*</span></label>
                    <select wire:model="doctor_id" class="form-select" required>
                        <option value="">Select Doctor</option>
                        @foreach($doctors as $id => $name)
                            <option value="{{ $id }}">{{ $name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <!-- Test Selection Section -->
            <div class="row mt-4">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                            <h6 class="mb-0 fw-semibold">
                                <i class="fas fa-x-ray me-2"></i>Test Requests
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
                                    <i class="fas fa-x-ray fa-3x mb-3 text-muted"></i>
                                    <h6 class="text-muted">No tests selected</h6>
                                    <p class="text-muted small">Click "Add Test" to start building your radiology test request.</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Summary Section -->
            @if(count($selected_tests) > 0)
                <div class="row mt-3">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="form-label fw-semibold">Discount (%)</label>
                            <input type="number" wire:model="discount_percent" class="form-control" min="0" max="100" step="0.01">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card bg-light">
                            <div class="card-body">
                                <h6 class="card-title">Summary</h6>
                                @php
                                    $totalAmount = 0;
                                    foreach($selected_tests as $testData) {
                                        if (!empty($testData['template_id'])) {
                                            $template = $available_templates->firstWhere('id', $testData['template_id']);
                                            if ($template) {
                                                $totalAmount += $template->standard_charge;
                                            }
                                        }
                                    }
                                    $discountAmount = ($totalAmount * $discount_percent) / 100;
                                                    $grandTotal = $totalAmount - $discountAmount;
                                @endphp
                                <div class="row">
                                    <div class="col-6">Subtotal:</div>
                                    <div class="col-6 text-end">{{ number_format($totalAmount, 2) }}</div>
                                </div>
                                <div class="row">
                                    <div class="col-6">Discount:</div>
                                    <div class="col-6 text-end">{{ number_format($discountAmount, 2) }}</div>
                                </div>
                                <hr>
                                <div class="row fw-bold">
                                    <div class="col-6">Total:</div>
                                    <div class="col-6 text-end">{{ number_format($grandTotal, 2) }}</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Additional Information -->
            <div class="row mt-4">
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Note</label>
                    <textarea wire:model="note" class="form-control" rows="3" placeholder="Additional notes..."></textarea>
                </div>
            </div>

            <!-- Form Validation Errors -->
            @error('patient_id') <span class="text-danger">{{ $message }}</span> @enderror
            @error('doctor_id') <span class="text-danger">{{ $message }}</span> @enderror
            @error('case_id') <span class="text-danger">{{ $message }}</span> @enderror
            @error('selected_tests') <span class="text-danger">{{ $message }}</span> @enderror

            <!-- Submit Buttons -->
            <div class="row mt-4">
                <div class="col-12">
                    <div class="d-flex justify-content-end gap-2">
                        <button type="button" wire:click="cancel" class="btn btn-secondary">
                            <i class="fas fa-times me-1"></i>Cancel
                        </button>
                        <button type="submit" class="btn btn-primary" {{ $isSubmitting ? 'disabled' : '' }}>
                            @if($isSubmitting)
                                <i class="fas fa-spinner fa-spin me-1"></i>Creating...
                            @else
                                <i class="fas fa-save me-1"></i>Create Radiology Test Request
                            @endif
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
