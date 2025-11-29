<div class="row">
    <div class="col-sm-12">
        <style>
            .medicineTable {
                margin-top: 15px;
            }
            .table-responsive {
                border-radius: 8px;
                box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            }
            .table thead {
                background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            }
            .table tbody tr {
                transition: all 0.3s ease;
                border-left: 4px solid transparent;
            }
            .table tbody tr:hover {
                background-color: #f8f9ff;
                border-left-color: #667eea;
                box-shadow: 0 2px 4px rgba(0,0,0,0.05);
            }
            .medicine-row-added {
                animation: slideInRow 0.5s cubic-bezier(0.34, 1.56, 0.64, 1);
                background: linear-gradient(90deg, rgba(102, 126, 234, 0.05) 0%, transparent 100%);
                border-left: 4px solid #667eea !important;
                box-shadow: 0 4px 12px rgba(102, 126, 234, 0.15) !important;
            }
            .medicine-row-added td {
                animation: fadeInCell 0.5s ease backwards;
            }
            .medicine-row-added td:nth-child(1) { animation-delay: 0.05s; }
            .medicine-row-added td:nth-child(2) { animation-delay: 0.1s; }
            .medicine-row-added td:nth-child(3) { animation-delay: 0.15s; }
            .medicine-row-added td:nth-child(4) { animation-delay: 0.2s; }
            .medicine-row-added td:nth-child(5) { animation-delay: 0.25s; }
            .medicine-row-added td:nth-child(6) { animation-delay: 0.3s; }
            .medicine-row-added td:nth-child(7) { animation-delay: 0.35s; }
            @keyframes slideInRow {
                from {
                    opacity: 0;
                    transform: translateY(-15px);
                }
                to {
                    opacity: 1;
                    transform: translateY(0);
                }
            }
            @keyframes fadeInCell {
                from {
                    opacity: 0;
                    transform: translateX(-10px);
                }
                to {
                    opacity: 1;
                    transform: translateX(0);
                }
            }
            @keyframes pulse {
                0%, 100% {
                    box-shadow: 0 0 0 0 rgba(102, 126, 234, 0.7);
                }
                50% {
                    box-shadow: 0 0 0 10px rgba(102, 126, 234, 0);
                }
            }
            .form-control:focus, .form-select:focus {
                border-color: #667eea;
                box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
            }
            .btn-add-medicine {
                background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
                border: none;
                transition: all 0.3s ease;
            }
            .btn-add-medicine:hover {
                transform: translateY(-2px);
                box-shadow: 0 4px 12px rgba(102, 126, 234, 0.4);
            }
            .btn-add-medicine:active {
                transform: translateY(0);
            }
            .new-row-badge {
                display: inline-block;
                background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
                color: white;
                padding: 2px 8px;
                border-radius: 12px;
                font-size: 11px;
                font-weight: bold;
                margin-left: 5px;
                animation: badgePop 0.4s cubic-bezier(0.34, 1.56, 0.64, 1);
            }
            @keyframes badgePop {
                from {
                    opacity: 0;
                    transform: scale(0.5);
                }
                to {
                    opacity: 1;
                    transform: scale(1);
                }
            }
            .medicine-row-added .new-row-badge {
                animation: badgePop 0.5s cubic-bezier(0.34, 1.56, 0.64, 1) 0.4s backwards;
            }
        </style>
        <div class="table-responsive medicineTable">
            <table class="table table-striped" id="prescriptionMedicalTbl">
                <thead class="thead-dark">
                    <tr class="text-start text-muted fw-bolder fs-7 text-uppercase gs-0">
                        <th>{{ __('messages.medicines') }} <span class="text-danger">*</span></th>
                        <th>{{ __('messages.ipd_patient_prescription.dosage') }} <span class="text-danger">*</span></th>
                        <th>{{ __('messages.purchase_medicine.dose_duration') }} <span class="text-danger">*</span></th>
                        <th>{{ __('messages.prescription.time') }} <span class="text-danger">*</span></th>
                        <th>{{ __('messages.medicine_bills.dose_interval') }} <span class="text-danger">*</span></th>
                        <th>{{ __('messages.prescription.comment') }}</th>
                        <th class="text-center">
                            <a href="javascript:void(0)" class="btn btn-primary btn-sm btn-add-medicine add-medicine-btn" id="addPrescriptionMedicineBtn">
                                <i class="fas fa-plus"></i> {{ __('messages.common.add') }}
                            </a>
                        </th>
                    </tr>
                </thead>
                <tbody class="prescription-medicine-container">
                    @php $index = 1; @endphp

                    @if (isset($prescription) && $prescription->getMedicine->count() > 0)
                        @foreach ($prescription->getMedicine as $med)
                            <tr data-row-id="{{ $index }}">
                                <td>
                                    <div class="form-group">
                                        {{ Form::select('medicine[]', $medicines['medicines'], $med->medicine, [
                                            'class' => 'form-select prescriptionMedicineId',
                                            'data-id' => $index,
                                            'required' => 'required',
                                            'placeholder' => 'Select Medicine'
                                        ]) }}
                                        <small class="d-block mt-1 text-sm">
                                            @if(isset($medicineQty[$med->medicine]))
                                                <span class="{{ $medicineQty[$med->medicine] == 0 ? 'text-danger' : 'text-success' }}">
                                                    Available: {{ $medicineQty[$med->medicine] }}
                                                </span>
                                            @endif
                                        </small>
                                    </div>
                                </td>
                                <td>
                                    {{ Form::text('dosage[]', $med->dosage, ['class' => 'form-control', 'placeholder' => 'e.g. 1 tab', 'required' => 'required']) }}
                                </td>
                                <td>
                                    {{ Form::select('day[]', \App\Models\Prescription::DOSE_DURATION, $med->day, ['class' => 'form-select', 'required' => 'required']) }}
                                </td>
                                <td>
                                    {{ Form::select('time[]', \App\Models\Prescription::MEAL_ARR, $med->time, ['class' => 'form-select', 'required' => 'required']) }}
                                </td>
                                <td>
                                    {{ Form::select('dose_interval[]', \App\Models\Prescription::DOSE_INTERVAL, $med->dose_interval, ['class' => 'form-select', 'required' => 'required']) }}
                                </td>
                                <td>
                                    {{ Form::textarea('comment[]', $med->comment, ['class' => 'form-control', 'rows' => 2, 'placeholder' => 'Optional']) }}
                                </td>
                                <td class="text-center">
                                    <a href="javascript:void(0)" class="btn btn-sm btn-danger delete-prescription-medicine-item">
                                        <i class="fas fa-trash-alt"></i>
                                    </a>
                                </td>
                            </tr>
                            @php $index++; @endphp
                        @endforeach
                    @else
                        <!-- Default First Row -->
                        <tr data-row-id="1">
                            <td>
                                <div class="form-group">
                                    {{ Form::select('medicine[]', $medicines['medicines'], null, [
                                        'class' => 'form-select prescriptionMedicineId',
                                        'data-id' => '1',
                                        'required' => 'required',
                                        'placeholder' => 'Select Medicine'
                                    ]) }}
                                    <small class="text-muted d-block mt-1">Available: <span class="stock-qty">-</span></small>
                                </div>
                            </td>
                            <td>
                                {{ Form::text('dosage[]', null, ['class' => 'form-control', 'placeholder' => 'e.g. 1 tablet', 'required' => 'required']) }}
                            </td>
                            <td>
                                {{ Form::select('day[]', \App\Models\Prescription::DOSE_DURATION, null, ['class' => 'form-select', 'required' => 'required']) }}
                            </td>
                            <td>
                                {{ Form::select('time[]', \App\Models\Prescription::MEAL_ARR, null, ['class' => 'form-select', 'required' => 'required']) }}
                            </td>
                            <td>
                                {{ Form::select('dose_interval[]', \App\Models\Prescription::DOSE_INTERVAL, null, ['class' => 'form-select', 'required' => 'required']) }}
                            </td>
                            <td>
                                {{ Form::textarea('comment[]', null, ['class' => 'form-control', 'rows' => 2, 'placeholder' => 'Optional comment']) }}
                            </td>
                            <td class="text-center">
                                <a href="javascript:void(0)" class="btn btn-sm btn-danger delete-prescription-medicine-item">
                                    <i class="fas fa-trash-alt"></i>
                                </a>
                            </td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const container = document.querySelector('.prescription-medicine-container');

    // Get next row ID dynamically
    function getNextRowId() {
        const rows = container.querySelectorAll('tr');
        return rows.length + 1;
    }

    // Check if last row is fully filled (including dosage)
    function isLastRowComplete() {
        const lastRow = container.querySelector('tr:last-child');
        if (!lastRow) return false;

        const medicine = lastRow.querySelector('select[name="medicine[]"]')?.value.trim() || '';
        const dosage = lastRow.querySelector('input[name="dosage[]"]')?.value.trim() || '';
        const day = lastRow.querySelector('select[name="day[]"]')?.value || '';
        const time = lastRow.querySelector('select[name="time[]"]')?.value || '';
        const interval = lastRow.querySelector('select[name="dose_interval[]"]')?.value || '';

        // Check all required fields are filled
        const isComplete = medicine && dosage && day && time && interval;

        if (!isComplete) {
            let missingFields = [];
            if (!medicine) missingFields.push('Medicine');
            if (!dosage) missingFields.push('Dosage');
            if (!day) missingFields.push('Duration');
            if (!time) missingFields.push('Time');
            if (!interval) missingFields.push('Interval');
            
            return { complete: false, missingFields };
        }

        return { complete: true };
    }

    // Add visual feedback to incomplete fields
    function highlightMissingFields() {
        const lastRow = container.querySelector('tr:last-child');
        if (!lastRow) return;

        lastRow.querySelectorAll('input[required], select[required]').forEach(field => {
            const value = field.tagName === 'SELECT' ? field.value : field.value.trim();
            if (!value) {
                field.classList.add('is-invalid');
                field.style.borderColor = '#dc3545';
            } else {
                field.classList.remove('is-invalid');
                field.style.borderColor = '';
            }
        });
    }

    // Add New Row
    document.getElementById('addPrescriptionMedicineBtn').addEventListener('click', function (e) {
        e.preventDefault();

        const validation = isLastRowComplete();
        
        if (!validation.complete) {
            const missingFields = validation.missingFields.join(', ');
            highlightMissingFields();
            
            Swal.fire({
                icon: 'warning',
                title: 'Missing Required Fields!',
                html: `<strong>Please fill the following fields before adding a new medicine:</strong><br><br><span style="color: #dc3545; font-weight: bold;">${missingFields}</span>`,
                confirmButtonColor: '#667eea',
                confirmButtonText: 'Okay',
                timer: 5000,
                timerProgressBar: true
            });
            return;
        }

        const rowId = getNextRowId();

        const newRow = document.createElement('tr');
        newRow.setAttribute('data-row-id', rowId);
        newRow.classList.add('medicine-row-added');
        newRow.innerHTML = `
            <td>
                <div class="form-group">
                    <select name="medicine[]" class="form-select prescriptionMedicineId" data-id="${rowId}" required>
                        <option value="">Select Medicine</option>
                        @foreach($medicines['medicines'] as $id => $name)
                            <option value="{{ $id }}">{{ $name }}</option>
                        @endforeach
                    </select>
                    <small class="text-muted d-block mt-1">Available: <span class="stock-qty">-</span></small>
                </div>
            </td>
            <td>
                <div class="position-relative">
                    <input type="text" name="dosage[]" class="form-control" placeholder="e.g. 1 tablet" required>
                    <span class="new-row-badge">NEW</span>
                </div>
            </td>
            <td>
                <select name="day[]" class="form-select" required>
                    <option value="">Select Duration</option>
                    @foreach(\App\Models\Prescription::DOSE_DURATION as $k => $v)
                        <option value="{{ $k }}">{{ $v }}</option>
                    @endforeach
                </select>
            </td>
            <td>
                <select name="time[]" class="form-select" required>
                    <option value="">Select Time</option>
                    @foreach(\App\Models\Prescription::MEAL_ARR as $k => $v)
                        <option value="{{ $k }}">{{ $v }}</option>
                    @endforeach
                </select>
            </td>
            <td>
                <select name="dose_interval[]" class="form-select" required>
                    <option value="">Select Interval</option>
                    @foreach(\App\Models\Prescription::DOSE_INTERVAL as $k => $v)
                        <option value="{{ $k }}">{{ $v }}</option>
                    @endforeach
                </select>
            </td>
            <td>
                <textarea name="comment[]" class="form-control" rows="2" placeholder="Optional comment"></textarea>
            </td>
            <td class="text-center">
                <a href="javascript:void(0)" class="btn btn-sm btn-danger delete-prescription-medicine-item" title="Delete medicine">
                    <i class="fas fa-trash-alt"></i>
                </a>
            </td>
        `;

        container.appendChild(newRow);

        // Show success animation with elegant notification
        Swal.fire({
            icon: 'success',
            title: '✨ Medicine Added!',
            html: '<p>New medicine row added successfully.</p><p style="font-size: 12px; color: #999; margin-top: 10px;">Fill in the details to add more medicines</p>',
            confirmButtonColor: '#667eea',
            confirmButtonText: 'Got it!',
            timer: 2500,
            timerProgressBar: true,
            showConfirmButton: false,
            backdrop: `
                rgba(102, 126, 234, 0.1)
                url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 1440 320'%3E%3Cpath fill='%23667eea' fill-opacity='0.05' d='M0,96L48,112C96,128,192,160,288,160C384,160,480,128,576,122.7C672,117,768,139,864,144C960,149,1056,139,1152,128C1248,117,1344,107,1392,101.3L1440,96L1440,320L1392,320C1344,320,1248,320,1152,320C1056,320,960,320,864,320C768,320,672,320,576,320C480,320,384,320,288,320C192,320,96,320,48,320L0,320Z'%3E%3C/path%3E%3C/svg%3E")
                no-repeat bottom
            `,
            didOpen: (modal) => {
                modal.style.borderRadius = '15px';
            }
        });

        // Remove highlight from previous row
        const prevRow = container.querySelector('tr:nth-last-child(2)');
        if (prevRow) {
            prevRow.querySelectorAll('input, select').forEach(field => {
                field.classList.remove('is-invalid');
                field.style.borderColor = '';
            });
        }

        // Scroll to new row smoothly
        setTimeout(() => {
            newRow.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
        }, 300);
    });

    // Delete Row
    document.addEventListener('click', function (e) {
        const btn = e.target.closest('.delete-prescription-medicine-item');
        if (!btn) return;

        const row = btn.closest('tr');
        const totalRows = container.querySelectorAll('tr').length;

        if (totalRows <= 1) {
            Swal.fire({
                icon: 'error',
                title: 'Cannot Delete',
                text: 'At least one medicine is required.',
                confirmButtonColor: '#667eea',
                timer: 3000
            });
            return;
        }

        row.remove();

        // Show deletion feedback
        Swal.fire({
            icon: 'info',
            title: 'Medicine Removed',
            text: 'The medicine row has been deleted.',
            confirmButtonColor: '#667eea',
            timer: 1500,
            showConfirmButton: false
        });
    });

    // Validate fields on input change
    document.addEventListener('change', function(e) {
        if (e.target.matches('input[required], select[required]')) {
            const value = e.target.tagName === 'SELECT' ? e.target.value : e.target.value.trim();
            if (value) {
                e.target.classList.remove('is-invalid');
                e.target.style.borderColor = '';
            }
        }
    });
});
</script>

<script>
// Enhanced validation for the add medicine button
document.addEventListener('turbo:load', function() {
    const container = document.querySelector('.prescription-medicine-container');
    if (!container) return;

    // Store original add medicine button click handler
    const addBtn = document.querySelector('.add-medicine-btn');
    if (!addBtn) return;

    // Create a wrapper to validate before adding
    const originalOnClick = addBtn.onclick;
    
    addBtn.addEventListener('click', function(e) {
        const lastRow = container.querySelector('tr:last-child');
        if (!lastRow) return;

        // Get field values
        const medicine = lastRow.querySelector('select[name="medicine[]"]')?.value.trim() || '';
        const dosage = lastRow.querySelector('input[name="dosage[]"]')?.value.trim() || '';
        const day = lastRow.querySelector('select[name="day[]"]')?.value || '';
        const time = lastRow.querySelector('select[name="time[]"]')?.value || '';
        const interval = lastRow.querySelector('select[name="dose_interval[]"]')?.value || '';

        const isComplete = medicine && dosage && day && time && interval;

        if (!isComplete) {
            let missingFields = [];
            if (!medicine) {
                missingFields.push('Medicine');
                highlightField(lastRow.querySelector('select[name="medicine[]"]'));
            }
            if (!dosage) {
                missingFields.push('Dosage');
                highlightField(lastRow.querySelector('input[name="dosage[]"]'));
            }
            if (!day) {
                missingFields.push('Duration');
                highlightField(lastRow.querySelector('select[name="day[]"]'));
            }
            if (!time) {
                missingFields.push('Time');
                highlightField(lastRow.querySelector('select[name="time[]"]'));
            }
            if (!interval) {
                missingFields.push('Interval');
                highlightField(lastRow.querySelector('select[name="dose_interval[]"]'));
            }
            
            const missingText = missingFields.join(', ');
            if (typeof Swal !== 'undefined') {
                Swal.fire({
                    icon: 'warning',
                    title: 'Missing Required Fields!',
                    html: `<strong>Please fill the following fields before adding a new medicine:</strong><br><br><span style="color: #dc3545; font-weight: bold;">${missingText}</span>`,
                    confirmButtonColor: '#667eea',
                    confirmButtonText: 'Okay',
                    timer: 5000,
                    timerProgressBar: true
                });
            }
            e.preventDefault();
            e.stopPropagation();
            return false;
        }
    });

    function highlightField(field) {
        if (field) {
            field.classList.add('is-invalid');
            field.style.borderColor = '#dc3545';
        }
    }

    // Watch for newly added rows
    const observer = new MutationObserver(function(mutations) {
        mutations.forEach(function(mutation) {
            if (mutation.type === 'childList') {
                mutation.addedNodes.forEach(node => {
                    if (node.tagName === 'TR') {
                        node.classList.add('medicine-row-added');
                        // Initialize select2 for new row if available
                        setTimeout(() => {
                            const selects = node.querySelectorAll('select');
                            if (typeof jQuery !== 'undefined' && jQuery.fn.select2) {
                                jQuery(selects).select2({
                                    width: '100%'
                                });
                            }
                            node.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
                        }, 300);
                    }
                });
            }
        });
    });

    observer.observe(container, {
        childList: true,
        subtree: false
    });
});
</script>