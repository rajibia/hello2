<div class="row">
    <div class="col-sm-12">
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
                            <a href="javascript:void(0)" class="btn btn-primary btn-sm" id="addPrescriptionMedicineBtn">
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

    // Check if last row is fully filled
    function isLastRowComplete() {
        const lastRow = container.querySelector('tr:last-child');
        if (!lastRow) return false;

        const medicine = lastRow.querySelector('select[name="medicine[]"]')?.value.trim() || '';
        const dosage = lastRow.querySelector('input[name="dosage[]"]')?.value.trim() || '';
        const day = lastRow.querySelector('select[name="day[]"]')?.value || '';
        const time = lastRow.querySelector('select[name="time[]"]')?.value || '';
        const interval = lastRow.querySelector('select[name="dose_interval[]"]')?.value || '';

        return medicine && dosage && day && time && interval;
    }

    // Add New Row
    document.getElementById('addPrescriptionMedicineBtn').addEventListener('click', function (e) {
        e.preventDefault();

        if (!isLastRowComplete()) {
            Swal.fire({
                icon: 'warning',
                title: 'Missing Required Fields!',
                text: 'Please fill all required fields (Medicine, Dosage, Duration, Time, Interval) before adding a new row.',
                confirmButtonText: 'Okay',
                timer: 5000,
                timerProgressBar: true
            });
            return;
        }

        const rowId = getNextRowId();

        const newRow = document.createElement('tr');
        newRow.setAttribute('data-row-id', rowId);
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
                <input type="text" name="dosage[]" class="form-control" placeholder="e.g. 1 tablet" required>
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
                <a href="javascript:void(0)" class="btn btn-sm btn-danger delete-prescription-medicine-item">
                    <i class="fas fa-trash-alt"></i>
                </a>
            </td>
        `;

        container.appendChild(newRow);
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
                timer: 3000
            });
            return;
        }

        row.remove();
    });
});
</script>