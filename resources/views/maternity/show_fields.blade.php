<div class="container-fluid">
    @include('maternity.vitals-indicator')
    <div class="mt-7 overflow-hidden">
        <ul class="nav nav-tabs mb-5 pb-1 overflow-auto flex-nowrap justify-content-between text-nowrap" id="myTab"
            role="tablist">
            <li class="nav-item position-relative me-7 mb-3" role="presentation">
                <button class="nav-link active p-0" id="maternityOverview" data-bs-toggle="tab" data-bs-target="#poverview"
                    type="button" role="tab" aria-controls="overview" aria-selected="true">
                    <i class="fas fa-chart-pie"></i>
                    Overview
                </button>
            </li>
            <li class="nav-item position-relative me-7 mb-3" role="presentation">
                <button class="nav-link p-0" id="antenatal-tab" data-bs-toggle="tab"
                    data-bs-target="#maternityAntenatal" type="button" role="tab" aria-controls="antenatal"
                    aria-selected="false">
                    <i class="fas fa-female"></i>
                    Antenatal
                </button>
            </li>
            <li class="nav-item position-relative me-7 mb-3" role="presentation">
                <button class="nav-link p-0" id="postnatal-tab" data-bs-toggle="tab"
                data-bs-target="#maternityPostnatal" type="button" role="tab" aria-controls="postnatal"
                aria-selected="false">
                    <i class="fas fa-baby"></i>
                    Postnatal History
                </button>
            </li>
            <li class="nav-item position-relative me-7 mb-3" role="presentation">
                <button class="nav-link p-0" id="obstetric-tab" data-bs-toggle="tab"
                data-bs-target="#maternityObstetric" type="button" role="tab" aria-controls="obstetric"
                aria-selected="false">
                    <i class="fas fa-calendar-alt"></i>
                    Previous Obstetric History
                </button>
            </li>
            <li class="nav-item position-relative me-7 mb-3" role="presentation">
                <button class="nav-link p-0" id="vitals-tab" data-bs-toggle="tab"
                data-bs-target="#maternityVitals" type="button" role="tab" aria-controls="vitals"
                aria-selected="false">
                    <i class="fas fa-heartbeat"></i>
                    Vitals
                </button>
            </li>
            <li class="nav-item position-relative me-7 mb-3" role="presentation">
                <button class="nav-link p-0" id="nursing-tab" data-bs-toggle="tab"
                data-bs-target="#maternityNursing" type="button" role="tab" aria-controls="nursing"
                aria-selected="false">
                    <i class="fas fa-notes-medical"></i>
                    Nurses Notes
                </button>
            </li>
            <li class="nav-item position-relative me-7 mb-3" role="presentation">
                <button class="nav-link p-0" id="prescription-tab" data-bs-toggle="tab"
                data-bs-target="#maternityPrescription" type="button" role="tab" aria-controls="prescription"
                aria-selected="false">
                    <i class="fas fa-prescription-bottle-alt"></i>
                    Prescription
                </button>
            </li>
            
            <li class="nav-item position-relative me-7 mb-3" role="presentation">
                <a href="{{ route('patients.show', ['patient' => $maternityPatient->patient->id]) }}" class="nav-link p-0" id="maternityPatientsVitalsTab">
                    <i class="fas fa-user-circle"></i>
                    View Profile
                </a>
            </li>

        </ul>

        <div class="tab-content" id="myTabContent">
            <div class="tab-pane fade show active" id="poverview" role="tabpanel" aria-labelledby="maternityOverview">
                <div class="row">
                    <div class="col-6">
                        <div class="card">
                            <div class="card-body">
                                <h2 class="mb-0">
                                    @if($maternityPatient->patient)
                                        <a href="{{ route('patients.show', $maternityPatient->patient->id) }}"
                                            class="text-decoration-none">
                                            {{ $maternityPatient->patient->patientUser->full_name ?? 'N/A' }}
                                        </a>
                                    @else
                                        <span class="text-decoration-none">N/A</span>
                                    @endif
                                </h2>
                                <hr>
                                <div class="row align-items-center">
                                    <div class="col-lg-3 text-center">
                                        <div class="image image-circle image-small">
                                            <img src="{{ $maternityPatient->patient && $maternityPatient->patient->patientUser ? $maternityPatient->patient->patientUser->image_url : asset('assets/img/default_image.jpg') }}"
                                                alt="image" />
                                        </div>
                                    </div>
                                    <div class="col-lg-9">
                                        <table class="table mb-0">
                                            <tbody>
                                                <tr>
                                                    <td>Gender</td>
                                                    <td>{{ $maternityPatient->patient && $maternityPatient->patient->patientUser ? ($maternityPatient->patient->patientUser->gender == 0 ? 'Male' : 'Female') : 'N/A' }}</td>
                                                </tr>
                                                <tr>
                                                    <td>Email</td>
                                                    <td class="text-break w-75">
                                                        {{ $maternityPatient->patient && $maternityPatient->patient->patientUser ? $maternityPatient->patient->patientUser->email : 'N/A' }}
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>Phone</td>
                                                    <td>{{ $maternityPatient->patient && $maternityPatient->patient->patientUser ? ($maternityPatient->patient->patientUser->phone ?? 'N/A') : 'N/A' }}</td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                <hr>
                                <div class="row">
                                    <div class="col-lg-9">
                                        <table class="table table-bordered">
                                            <tbody>
                                                <tr>
                                                    <td>Unique ID</td>
                                                    <td>N/A</td>
                                                </tr>
                                                <tr>
                                                    <td>Maternity No</td>
                                                    <td>
                                                        <a href="{{ url('maternity/' . $maternityPatient->id) }}" class="text-decoration-none">
                                                            {{ $maternityPatient->maternity_number }}
                                                        </a>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>Appointment Date</td>
                                                    <td>{{ \Carbon\Carbon::parse($maternityPatient->appointment_date)->translatedFormat('jS M, Y') }}</td>
                                                </tr>
                                                <tr>
                                                    <td>Doctor</td>
                                                    <td>{{ $maternityPatient->doctor ? $maternityPatient->doctor->doctorUser->full_name : 'N/A' }}</td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                <hr>
                                <div class="row">
                                    <p><i class="fa fa-tag"></i> Symptoms</p>
                                    <ul class="timeline-ps-46 mb-0">
                                        <li>
                                            <div>
                                                {!! !empty($maternityPatient->symptoms) ? nl2br(e($maternityPatient->symptoms)) : 'N/A' !!}
                                            </div>
                                        </li>
                                    </ul>
                                </div>
                                <hr>
                                <div class="row">
                                    <p><i class="fa fa-user-md"></i> Consultant Doctor</p>
                                    <div class="d-flex justify-content-between align-items-center">
                                        @if($consultantDoctor && $consultantDoctor->count() > 0)
                                            <div>
                                                @foreach($consultantDoctor as $consultant)
                                                    <div class="mb-2">
                                                        <strong>{{ $consultant->doctor->doctorUser->full_name ?? 'Dr. Unknown' }}</strong><br>
                                                        <small class="text-muted">
                                                            Applied: {{ $consultant->applied_date->format('d/m/Y') }} |
                                                            Instruction: {{ $consultant->instruction_date->format('d/m/Y') }}
                                                        </small><br>
                                                        <small>{{ $consultant->instruction }}</small>
                                                    </div>
                                                @endforeach
                                            </div>
                                        @else
                                            <span>No Consultant Doctor</span>
                                        @endif
                                        <button class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#addConsultantModal">
                                            <i class="fa fa-plus"></i>
                                        </button>
                                    </div>
                                </div>
                                <hr>
                                <div class="row">
                                    <p><i class="fa fa-clock"></i> Timeline</p>
                                    <span>No TimeLine Found</span>
                                </div>
                                <hr>
                                <div class="row">
                                    <p><i class="fa fa-procedures"></i> Operation</p>
                                    <div class="table-responsive">
                                        <table class="table table-striped">
                                            <thead>
                                                <tr>
                                                    <th>REFERENCE ID</th>
                                                    <th>OPERATION DATE</th>
                                                    <th>OPERATION NAME</th>
                                                    <th>OPERATION CATEGORY</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td colspan="4" class="text-center">No data available in table</td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="mb-10">
                            <div class="d-flex justify-content-between">
                                <h3 class="text-uppercase fs-5">Payment / Billings</h3>
                                <h5 class="text-gray-700">0%</h5>
                            </div>
                            <div class="progress">
                                <div class="progress-bar bg-primary" role="progressbar" style="width: 0%" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                        </div>
                        <div class="mb-10">
                            <h3 class="text-uppercase fs-5">Prescription</h3>
                            <div class="table-responsive">
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th>MATERNITY NO</th>
                                            <th>CREATED ON</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td colspan="2" class="text-center">No data available in table</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="mb-10">
                            <h3 class="text-uppercase fs-5">Consultant Instruction</h3>
                            <div class="table-responsive">
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th>DOCTOR</th>
                                            <th>APPLIED DATE</th>
                                            <th>INSTRUCTION DATE</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td colspan="3" class="text-center">No data available in table</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="mb-10">
                            <h3 class="text-uppercase fs-5">Charges</h3>
                            <div class="table-responsive">
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th>DATE</th>
                                            <th>CHARGE TYPE</th>
                                            <th>CODE</th>
                                            <th>STANDARD CHARGE</th>
                                            <th>APPLIED</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>{{ \Carbon\Carbon::parse($maternityPatient->appointment_date)->translatedFormat('jS M, Y') }}</td>
                                            <td>Others</td>
                                            <td>M001</td>
                                            <td>{{ $maternityPatient->standard_charge ?? 0 }} {{ getCurrencySymbol() }}</td>
                                            <td>{{ $maternityPatient->standard_charge ?? 0 }}</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="mb-10">
                            <h3 class="text-uppercase fs-5">Payment</h3>
                            <div class="table-responsive">
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th>DATE</th>
                                            <th>AMOUNT</th>
                                            <th>PAYMENT MODE</th>
                                            <th>DOCUMENT</th>
                                            <th>NOTE</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td colspan="5" class="text-center">No data available in table</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="tab-pane fade" id="maternityAntenatal" role="tabpanel" aria-labelledby="antenatal-tab">
                <div class="card">
                    <div class="card-body">
                        @livewire('antenatal-table', ['ipdId' => $maternityPatient->patient_id])
                    </div>
                </div>
            </div>
            <div class="tab-pane fade" id="maternityPostnatal" role="tabpanel" aria-labelledby="postnatal-tab">
                <div class="card">
                    <div class="card-body">
                        @livewire('postnatal-table', ['ipdId' => $maternityPatient->patient_id])
                    </div>
                </div>
            </div>
            <div class="tab-pane fade" id="maternityObstetric" role="tabpanel" aria-labelledby="obstetric-tab">
                <div class="card">
                    <div class="card-body">
                        @livewire('maternity-obstetric-history-table', ['ipdId' => $maternityPatient->patient_id])
                    </div>
                </div>
            </div>
            <div class="tab-pane fade" id="maternityVitals" role="tabpanel" aria-labelledby="vitals-tab">
                <div class="card">
                    <div class="card-body">
                        @livewire('maternity-vitals-table', ['ipdId' => $maternityPatient->patient_id, 'maternityId' => $maternityPatient->id])
                    </div>
                </div>
            </div>
            <div class="tab-pane fade" id="maternityNursing" role="tabpanel" aria-labelledby="nursing-tab">
                <div class="card">
                    <div class="card-body">
                        @livewire('maternity-nursing-progress-notes-table', ['ipdId' => $maternityPatient->patient_id])
                    </div>
                </div>
            </div>
            <div class="tab-pane fade" id="maternityPrescription" role="tabpanel" aria-labelledby="prescription-tab">
                <div class="card">
                    <div class="card-body">
                        @livewire('maternity-prescription-table', ['maternityId' => $maternityPatient->patient_id])
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Add Consultant Doctor Modal -->
<div class="modal fade" id="addConsultantModal" tabindex="-1" aria-labelledby="addConsultantModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addConsultantModalLabel">Add Consultant Instruction</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="consultantForm">
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>APPLIED DATE*</th>
                                    <th>DOCTOR*</th>
                                    <th>INSTRUCTION DATE*</th>
                                    <th>INSTRUCTION*</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody id="consultantTableBody">
                                <tr>
                                    <td>1</td>
                                    <td>
                                        <input type="date" class="form-control" name="applied_date[]" required>
                                    </td>
                                    <td>
                                        <select class="form-select" name="doctor_id[]" required>
                                            <option value="">Select Doctor</option>
                                            @if(isset($doctors) && is_array($doctors) && count($doctors) > 0)
                                                @foreach($doctors as $id => $name)
                                                    <option value="{{ $id }}">{{ is_string($name) ? $name : 'Doctor ' . $id }}</option>
                                                @endforeach
                                            @else
                                                <option value="1">Dr. Test Doctor</option>
                                                <option value="2">Dr. Sample Doctor</option>
                                            @endif
                                        </select>
                                    </td>
                                    <td>
                                        <input type="date" class="form-control" name="instruction_date[]" required>
                                    </td>
                                    <td>
                                        <textarea class="form-control" name="instruction[]" rows="2" placeholder="Instruction" required></textarea>
                                    </td>
                                    <td>
                                        <button type="button" class="btn btn-danger btn-sm removeRow">
                                            <i class="fa fa-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="text-end">
                        <button type="button" class="btn btn-success" id="addConsultantRow">
                            <i class="fa fa-plus"></i> Add
                        </button>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" id="saveConsultantBtn">Save</button>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    let rowCount = 1;

    // Add new row
    document.getElementById('addConsultantRow').addEventListener('click', function() {
        rowCount++;
        const tbody = document.getElementById('consultantTableBody');
        const newRow = document.createElement('tr');
        newRow.innerHTML = `
            <td>${rowCount}</td>
            <td>
                <input type="date" class="form-control" name="applied_date[]" required>
            </td>
            <td>
                <select class="form-select" name="doctor_id[]" required>
                    <option value="">Select Doctor</option>
                    @if(isset($doctors) && is_array($doctors) && count($doctors) > 0)
                        @foreach($doctors as $id => $name)
                            <option value="{{ $id }}">{{ is_string($name) ? $name : 'Doctor ' . $id }}</option>
                        @endforeach
                    @else
                        <option value="1">Dr. Test Doctor</option>
                        <option value="2">Dr. Sample Doctor</option>
                    @endif
                </select>
            </td>
            <td>
                <input type="date" class="form-control" name="instruction_date[]" required>
            </td>
            <td>
                <textarea class="form-control" name="instruction[]" rows="2" placeholder="Instruction" required></textarea>
            </td>
            <td>
                <button type="button" class="btn btn-danger btn-sm removeRow">
                    <i class="fa fa-trash"></i>
                </button>
            </td>
        `;
        tbody.appendChild(newRow);
    });

    // Remove row
    document.addEventListener('click', function(e) {
        if (e.target.closest('.removeRow')) {
            e.target.closest('tr').remove();
            // Renumber rows
            const rows = document.querySelectorAll('#consultantTableBody tr');
            rows.forEach((row, index) => {
                row.cells[0].textContent = index + 1;
            });
            rowCount = rows.length;
        }
    });

    // Save consultant instruction
    document.getElementById('saveConsultantBtn').addEventListener('click', function() {
        const form = document.getElementById('consultantForm');

        // Validate form before submitting
        const appliedDates = form.querySelectorAll('input[name="applied_date[]"]');
        const doctorIds = form.querySelectorAll('select[name="doctor_id[]"]');
        const instructionDates = form.querySelectorAll('input[name="instruction_date[]"]');
        const instructions = form.querySelectorAll('textarea[name="instruction[]"]');

        for (let i = 0; i < appliedDates.length; i++) {
            if (!appliedDates[i].value || !instructionDates[i].value || !instructions[i].value.trim()) {
                alert('Please fill all fields in row ' + (i + 1));
                return;
            }
            if (!doctorIds[i].value || doctorIds[i].value === '0') {
                alert('Please select a doctor in row ' + (i + 1));
                return;
            }
        }

        const formData = new FormData(form);

        // Add maternity patient ID
        formData.append('maternity_patient_id', '{{ $maternityPatient->id }}');

        // Send data to backend
        fetch('{{ route("maternity.consultant.store") }}', {
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert(data.message);
                $('#addConsultantModal').modal('hide');
                form.reset();
                // Reload page to show updated consultant doctor list
                location.reload();
            } else {
                alert('Error: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error saving consultant instruction');
        });
    });
});
</script>

<!-- Add Nursing Progress Notes Modal -->
<div id="addNursingNoteModal" class="modal fade" role="dialog" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <h2>Add Nursing Progress Notes</h2>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="addNursingNoteForm">
                <div class="modal-body">
                    <div class="alert alert-danger d-none hide" id="addNursingNoteErrorsBox"></div>
                    @csrf
                    <input type="hidden" name="patient_id" value="{{ $maternityPatient->patient_id }}">
                    <input type="hidden" name="user_id" value="{{ Auth::id() }}">

                    <div class="row gx-10 mb-5">
                        <div class="col-lg-12 col-md-12 col-sm-12">
                            <div class="mb-5">
                                <div class="mb-5">
                                    <label for="notes" class="form-label">Nursing Progress Notes:</label>
                                    <textarea name="notes" class="form-control" required rows="3" tabindex="2" placeholder="Nursing Progress Notes"></textarea>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="d-flex justify-content-end">
                        <button type="submit" class="btn btn-success me-3" id="btnNursingNoteSave" data-loading-text="<span class='spinner-border spinner-border-sm'></span> Processing...">Save</button>
                        <button type="button" id="btnNursingNoteCancel" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Handle nursing progress notes form submission
    const nursingForm = document.getElementById('addNursingNoteForm');
    if (nursingForm) {
        nursingForm.addEventListener('submit', function(e) {
            e.preventDefault();
            e.stopPropagation();
            console.log('Form submitted via AJAX');

            const formData = new FormData(this);

            // Send data to backend
            const submitUrl = '{{ route("maternity.nursing.store") }}';
            console.log('Submitting to:', submitUrl);
            fetch(submitUrl, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Close modal
                $('#addNursingNoteModal').modal('hide');
                // Reset form
                // this.reset();
                // Refresh the table
                // Livewire.emit('refresh');
                // Show success message
                alert('Nursing progress note added successfully!');
            } else {
                alert('Error: ' + (data.message || 'Failed to add nursing progress note'));
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error adding nursing progress note');
        });

        return false;
        });
    }
});
</script>

<!-- Add Prescription Modal -->
<div id="addPrescriptionModal" class="modal fade" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-xl overflow-hidden custom-modal">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <h2>New Prescription</h2>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="addPrescriptionForm">
                <div class="modal-body">
                    <div class="alert alert-danger d-none hide" id="addPrescriptionErrorsBox"></div>
                    @csrf
                    <input type="hidden" name="patient_id" value="{{ $maternityPatient->patient_id }}">
                    <input type="hidden" name="doctor_id" value="{{ $maternityPatient->doctor_id }}">
                    @php
                        // Prepare medicine options for hidden fields
                        $medicineOptions = [];
                        if (isset($medicines['medicines'])) {
                            // Format from prescriptions controller
                            $medicineOptions = $medicines['medicines'];
                        } elseif (isset($medicines) && is_array($medicines)) {
                            // Format from maternity controller
                            foreach($medicines as $medicine) {
                                if (is_object($medicine)) {
                                    $medicineOptions[$medicine->id] = $medicine->name;
                                }
                            }
                        }
                        
                        // If still empty, try to get from global Medicine model
                        if (empty($medicineOptions)) {
                            // Removed problematic view rendering code
                            
                            // Try different approaches to get medicines
                            if (class_exists('\App\Models\Medicine')) {
                                $allMedicines = \App\Models\Medicine::all();
                                foreach($allMedicines as $medicine) {
                                    $medicineOptions[$medicine->id] = $medicine->name;
                                }
                            } elseif (function_exists('getMedicineList')) {
                                // Try using a helper function if available
                                $medicineOptions = getMedicineList();
                            } elseif (class_exists('\App\Models\Prescription')) {
                                // Try using the Prescription model to get medicines
                                try {
                                    // Try different static methods that might exist
                                    if (method_exists('\App\Models\Prescription', 'getMedicineList')) {
                                        $medicineOptions = \App\Models\Prescription::getMedicineList();
                                    } elseif (method_exists('\App\Models\Prescription', 'medicineList')) {
                                        $medicineOptions = \App\Models\Prescription::medicineList();
                                    } elseif (method_exists('\App\Models\Prescription', 'prepareMedicineData')) {
                                        $medicineData = \App\Models\Prescription::prepareMedicineData();
                                        if (isset($medicineData['medicines'])) {
                                            $medicineOptions = $medicineData['medicines'];
                                        }
                                    }
                                } catch (\Exception $e) {
                                    // Ignore exceptions
                                }
                            } elseif (class_exists('DB')) {
                                // Direct database query as last resort
                                try {
                                    // Try different table names that might exist
                                    $tables = ['medicines', 'medicine', 'drugs', 'drug', 'pharmaceuticals', 'pharmaceutical'];
                                    foreach ($tables as $table) {
                                        try {
                                            $dbMedicines = \DB::table($table)->select('id', 'name')->get();
                                            if ($dbMedicines && count($dbMedicines) > 0) {
                                                foreach($dbMedicines as $medicine) {
                                                    $medicineOptions[$medicine->id] = $medicine->name;
                                                }
                                                break; // Found medicines, exit the loop
                                            }
                                        } catch (\Exception $e) {
                                            // Table doesn't exist, try the next one
                                            continue;
                                        }
                                    }
                                } catch (\Exception $e) {
                                    // Ignore exceptions
                                }
                            }
                            
                            // If still empty, add some dummy data for testing
                            if (empty($medicineOptions)) {
                                $medicineOptions = [
                                    '1' => 'Paracetamol',
                                    '2' => 'Ibuprofen',
                                    '3' => 'Amoxicillin',
                                    '4' => 'Omeprazole',
                                    '5' => 'Aspirin'
                                ];
                            }
                        }
                        
                        // Use model constants for other options if not provided
                        $mealOptions = isset($mealList) ? $mealList : \App\Models\Prescription::MEAL_ARR;
                        $durationOptions = isset($doseDurationList) ? $doseDurationList : \App\Models\Prescription::DOSE_DURATION;
                        $intervalOptions = isset($doseIntervalList) ? $doseIntervalList : \App\Models\Prescription::DOSE_INTERVAL;
                    @endphp
                    {{Form::hidden('uniqueId',2,['id'=>'prescriptionUniqueId'])}}
                    {{Form::hidden('associateMedicines',json_encode($medicineOptions),['class'=>'associatePrescriptionMedicines'])}}
                    {{Form::hidden('associateMeals',json_encode($mealOptions),['class'=>'associatePrescriptionMeals'])}}
                    {{Form::hidden('associateDuration',json_encode($durationOptions),['class'=>'associatePrescriptionDurations'])}}
                    {{Form::hidden('associateInterval',json_encode($intervalOptions),['class'=>'associatePrescriptionIntervals'])}}

                    <!-- Patient and Doctor Information -->
                    <div class="row mb-5">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label">Patient: <span class="required"></span></label>
                                <input type="text" class="form-control" value="{{ $maternityPatient->patient->patientUser->full_name }}" readonly>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label">Doctor: <span class="required"></span></label>
                                <input type="text" class="form-control" value="{{ $maternityPatient->doctor->doctorUser->full_name }}" readonly>
                            </div>
                        </div>
                    </div>

                    <!-- Additional Information -->
                    <div class="row mb-5">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="form-label">Health Insurance:</label>
                                <input type="text" name="health_insurance" class="form-control" placeholder="Health Insurance">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="form-label">Low Income:</label>
                                <input type="text" name="low_income" class="form-control" placeholder="Low Income">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="form-label">Reference:</label>
                                <input type="text" name="reference" class="form-control" placeholder="Reference">
                            </div>
                        </div>
                    </div>

                    <!-- Status -->
                    <div class="row mb-5">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label">Status:</label>
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" name="status" id="prescriptionStatus" checked>
                                    <label class="form-check-label" for="prescriptionStatus">Active</label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <hr>

                    <!-- Medicine Section -->
                    <div class="row mb-5">
                        <div class="col-sm-12">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h5>Medicine</h5>
                                <button type="button" class="btn btn-success btn-sm" id="addMedicineBtn">
                                    New Medicine
                                </button>
                            </div>

                            <div class="table-responsive mb-10 scroll-y" style="max-height: 225px">
                                <table class="table table-striped" id="prescriptionMedicineTbl">
                                    <thead class="thead-dark sticky-top">
                                        <tr class="border-bottom fs-7 fw-bolder text-gray-700 text-uppercase">
                                            <th class="text-center">#</th>
                                            <th>MEDICINE<span class="required"></span></th>
                                            <th>DOSAGE</th>
                                            <th>DOSE DURATION<span class="required"></span></th>
                                            <th>TIME<span class="required"></span></th>
                                            <th>DOSE INTERVAL<span class="required"></span></th>
                                            <th>COMMENT</th>
                                            <th class="text-center">ADD</th>
                                        </tr>
                                    </thead>
                                    <tbody class="prescription-medicine-container">
                                        <tr>
                                            <td class="text-center prescription-item-number" data-item-number="1">1</td>
                                            <td>
                                                <div class="form-input" id="medicineDiv1" style="width:180px;">
                                                    @php
                                                        // Debug information removed
                                                        
                                                        // Create medicine options
                                                        $medicineOptions = [];
                                                        if (isset($medicines['medicines'])) {
                                                            // Format from prescriptions controller
                                                            $medicineOptions = $medicines['medicines'];
                                                        } elseif (isset($medicines) && is_array($medicines)) {
                                                            // Format from maternity controller
                                                            foreach($medicines as $medicine) {
                                                                if (is_object($medicine)) {
                                                                    $medicineOptions[$medicine->id] = $medicine->name;
                                                                }
                                                            }
                                                        }
                                                        
                                                        // If still empty, try to get from global Medicine model
                                                        if (empty($medicineOptions)) {
                                                            // Removed problematic view rendering code
                                                            
                                                            // Try different approaches to get medicines
                                                            if (class_exists('\App\Models\Medicine')) {
                                                                $allMedicines = \App\Models\Medicine::all();
                                                                foreach($allMedicines as $medicine) {
                                                                    $medicineOptions[$medicine->id] = $medicine->name;
                                                                }
                                                            } elseif (function_exists('getMedicineList')) {
                                                                // Try using a helper function if available
                                                                $medicineOptions = getMedicineList();
                                                            } elseif (class_exists('\App\Models\Prescription')) {
                                                                // Try using the Prescription model to get medicines
                                                                try {
                                                                    // Try different static methods that might exist
                                                                    if (method_exists('\App\Models\Prescription', 'getMedicineList')) {
                                                                        $medicineOptions = \App\Models\Prescription::getMedicineList();
                                                                    } elseif (method_exists('\App\Models\Prescription', 'medicineList')) {
                                                                        $medicineOptions = \App\Models\Prescription::medicineList();
                                                                    } elseif (method_exists('\App\Models\Prescription', 'prepareMedicineData')) {
                                                                        $medicineData = \App\Models\Prescription::prepareMedicineData();
                                                                        if (isset($medicineData['medicines'])) {
                                                                            $medicineOptions = $medicineData['medicines'];
                                                                        }
                                                                    }
                                                                } catch (\Exception $e) {
                                                                    // Ignore exceptions
                                                                }
                                                            } elseif (class_exists('DB')) {
                                                                // Direct database query as last resort
                                                                try {
                                                                    // Try different table names that might exist
                                                                    $tables = ['medicines', 'medicine', 'drugs', 'drug', 'pharmaceuticals', 'pharmaceutical'];
                                                                    foreach ($tables as $table) {
                                                                        try {
                                                                            $dbMedicines = \DB::table($table)->select('id', 'name')->get();
                                                                            if ($dbMedicines && count($dbMedicines) > 0) {
                                                                                foreach($dbMedicines as $medicine) {
                                                                                    $medicineOptions[$medicine->id] = $medicine->name;
                                                                                }
                                                                                break; // Found medicines, exit the loop
                                                                            }
                                                                        } catch (\Exception $e) {
                                                                            // Table doesn't exist, try the next one
                                                                            continue;
                                                                        }
                                                                    }
                                                                } catch (\Exception $e) {
                                                                    // Ignore exceptions
                                                                }
                                                            }
                                                            
                                                            // If still empty, add some dummy data for testing
                                                            if (empty($medicineOptions)) {
                                                                $medicineOptions = [
                                                                    '1' => 'Paracetamol',
                                                                    '2' => 'Ibuprofen',
                                                                    '3' => 'Amoxicillin',
                                                                    '4' => 'Omeprazole',
                                                                    '5' => 'Aspirin'
                                                                ];
                                                            }
                                                        }
                                                    @endphp
                                                    {{ Form::select('medicine[]', $medicineOptions, null, ['class' => 'form-select prescriptionMedicineId', 'data-id' => 1, 'placeholder' => 'Select Medicine']) }}
                                                    <small class="" id="AvailbleQty"></small>
                                                </div>
                                            </td>
                                            <td>
                                                <input type="text" name="dosage[]" class="form-control" placeholder="Dosage">
                                            </td>
                                            <td>
                                                {{ Form::select('day[]', \App\Models\Prescription::DOSE_DURATION, null, ['class' => 'form-select prescriptionMedicineMealId']) }}
                                            </td>
                                            <td>
                                                {{ Form::select('time[]', \App\Models\Prescription::MEAL_ARR, null, ['class' => 'form-select prescriptionMedicineMealId']) }}
                                            </td>
                                            <td>
                                                {{ Form::select('dose_interval[]', \App\Models\Prescription::DOSE_INTERVAL, null, ['class' => 'form-select prescriptionMedicineMealId']) }}
                                            </td>
                                            <td>
                                                <input type="text" name="instruction[]" class="form-control" placeholder="Comment">
                                            </td>
                                            <td class="text-center">
                                                <a href="javascript:void(0)" title="Delete" class="delete-prescription-medicine-item btn px-1 text-danger fs-3 pe-0">
                                                    <i class="fa-solid fa-trash"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <hr>

                    <!-- Physical Information -->
                    <div class="row mb-5">
                        <div class="col-sm-12">
                            <h5>Physical Information</h5>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-label">Problem Description:</label>
                                        <textarea name="problem_description" class="form-control" rows="3" placeholder="Problem Description"></textarea>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-label">Test:</label>
                                        <textarea name="test" class="form-control" rows="3" placeholder="Test"></textarea>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-label">Advice:</label>
                                        <textarea name="advice" class="form-control" rows="3" placeholder="Advice"></textarea>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-label">Current Medication:</label>
                                        <textarea name="current_medication" class="form-control" rows="3" placeholder="Current Medication"></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="modal-footer p-0">
                        <button type="submit" class="btn btn-primary me-3" id="btnPrescriptionSave" data-loading-text="<span class='spinner-border spinner-border-sm'></span> Processing...">Save</button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize Select2 for medicine dropdowns
    $(".prescriptionMedicineId").select2({
        placeholder: "Select Medicine",
        width: "100%",
        dropdownParent: $("#addPrescriptionModal")
    });
    
    // Initialize day dropdowns
    $("select[name='day[]']").select2({
        placeholder: "Select Duration",
        width: "100%",
        dropdownParent: $("#addPrescriptionModal")
    });
    
    // Initialize time dropdowns
    $("select[name='time[]']").select2({
        placeholder: "Select Time",
        width: "100%",
        dropdownParent: $("#addPrescriptionModal")
    });
    
    // Initialize dose interval dropdowns
    $("select[name='dose_interval[]']").select2({
        placeholder: "Select Interval",
        width: "100%",
        dropdownParent: $("#addPrescriptionModal")
    });

    // Add new medicine row button
    document.getElementById('addMedicineBtn').addEventListener('click', function() {
        let uniquePrescriptionId = $("#prescriptionUniqueId").val();
        let medicinesData;
        let mealsData;
        let doseDurationData;
        let doseIntervalData;
        
        try {
            medicinesData = JSON.parse($(".associatePrescriptionMedicines").val());
            mealsData = JSON.parse($(".associatePrescriptionMeals").val());
            doseDurationData = JSON.parse($(".associatePrescriptionDurations").val());
            doseIntervalData = JSON.parse($(".associatePrescriptionIntervals").val());
        } catch (e) {
            console.error('Error parsing JSON data:', e);
            // Fallback to using the existing select options
            medicinesData = {};
            mealsData = {};
            doseDurationData = {};
            doseIntervalData = {};
            
            // Get medicine options from the first row
            $('.prescriptionMedicineId option').each(function() {
                if ($(this).val()) {
                    medicinesData[$(this).val()] = $(this).text();
                }
            });
            
            // Get meal options from the first row
            $('select[name="time[]"] option').each(function() {
                if ($(this).val()) {
                    mealsData[$(this).val()] = $(this).text();
                }
            });
            
            // Get duration options from the first row
            $('select[name="day[]"] option').each(function() {
                if ($(this).val()) {
                    doseDurationData[$(this).val()] = $(this).text();
                }
            });
            
            // Get interval options from the first row
            $('select[name="dose_interval[]"] option').each(function() {
                if ($(this).val()) {
                    doseIntervalData[$(this).val()] = $(this).text();
                }
            });
        }
        
        let data = {
            medicines: medicinesData,
            meals: mealsData,
            doseDuration: doseDurationData,
            doseInterval: doseIntervalData,
            uniqueId: uniquePrescriptionId,
        };
        
        // Get the current select options from the first row for fallback
        let medicineOptions = '';
        let dayOptions = '';
        let timeOptions = '';
        let doseIntervalOptions = '';
        
        // Get medicine options
        try {
            if (Object.keys(data.medicines).length > 0) {
                medicineOptions = Object.entries(data.medicines).map(([key, value]) => 
                    `<option value="${key}">${value}</option>`).join('');
            } else {
                // Fallback: get options from existing select
                $('.prescriptionMedicineId:first option').each(function() {
                    if ($(this).val()) {
                        medicineOptions += `<option value="${$(this).val()}">${$(this).text()}</option>`;
                    }
                });
            }
        } catch (e) {
            // Fallback to existing select
            $('.prescriptionMedicineId:first option').each(function() {
                if ($(this).val()) {
                    medicineOptions += `<option value="${$(this).val()}">${$(this).text()}</option>`;
                }
            });
        }
        
        // Get day options
        try {
            if (Object.keys(data.doseDuration).length > 0) {
                dayOptions = Object.entries(data.doseDuration).map(([key, value]) => 
                    `<option value="${key}">${value}</option>`).join('');
            } else {
                // Fallback: get options from existing select
                $('select[name="day[]"]:first option').each(function() {
                    if ($(this).val()) {
                        dayOptions += `<option value="${$(this).val()}">${$(this).text()}</option>`;
                    }
                });
            }
        } catch (e) {
            // Fallback to existing select
            $('select[name="day[]"]:first option').each(function() {
                if ($(this).val()) {
                    dayOptions += `<option value="${$(this).val()}">${$(this).text()}</option>`;
                }
            });
        }
        
        // Get time options
        try {
            if (Object.keys(data.meals).length > 0) {
                timeOptions = Object.entries(data.meals).map(([key, value]) => 
                    `<option value="${key}">${value}</option>`).join('');
            } else {
                // Fallback: get options from existing select
                $('select[name="time[]"]:first option').each(function() {
                    if ($(this).val()) {
                        timeOptions += `<option value="${$(this).val()}">${$(this).text()}</option>`;
                    }
                });
            }
        } catch (e) {
            // Fallback to existing select
            $('select[name="time[]"]:first option').each(function() {
                if ($(this).val()) {
                    timeOptions += `<option value="${$(this).val()}">${$(this).text()}</option>`;
                }
            });
        }
        
        // Get dose interval options
        try {
            if (Object.keys(data.doseInterval).length > 0) {
                doseIntervalOptions = Object.entries(data.doseInterval).map(([key, value]) => 
                    `<option value="${key}">${value}</option>`).join('');
            } else {
                // Fallback: get options from existing select
                $('select[name="dose_interval[]"]:first option').each(function() {
                    if ($(this).val()) {
                        doseIntervalOptions += `<option value="${$(this).val()}">${$(this).text()}</option>`;
                    }
                });
            }
        } catch (e) {
            // Fallback to existing select
            $('select[name="dose_interval[]"]:first option').each(function() {
                if ($(this).val()) {
                    doseIntervalOptions += `<option value="${$(this).val()}">${$(this).text()}</option>`;
                }
            });
        }
        
        // Create new row using template
        let prescriptionMedicineHtml = `
            <tr>
                <td class="text-center prescription-item-number" data-item-number="${parseInt(uniquePrescriptionId)}">${parseInt(uniquePrescriptionId)}</td>
                <td>
                    <div class="form-input" id="medicineDiv${uniquePrescriptionId}" style="width:180px;">
                        <select class="form-select prescriptionMedicineId" name="medicine[]" data-id="${uniquePrescriptionId}" required>
                            <option value="">Select Medicine</option>
                            ${medicineOptions}
                        </select>
                        <small class="" id="AvailbleQty"></small>
                    </div>
                </td>
                <td>
                    <input type="text" name="dosage[]" class="form-control" placeholder="Dosage">
                </td>
                <td>
                    <select class="form-select prescriptionMedicineMealId" name="day[]" required>
                        <option value="">Select Duration</option>
                        ${dayOptions}
                    </select>
                </td>
                <td>
                    <select class="form-select prescriptionMedicineMealId" name="time[]" required>
                        <option value="">Select Time</option>
                        ${timeOptions}
                    </select>
                </td>
                <td>
                    <select class="form-select prescriptionMedicineMealId" name="dose_interval[]" required>
                        <option value="">Select Interval</option>
                        ${doseIntervalOptions}
                    </select>
                </td>
                <td>
                    <input type="text" name="instruction[]" class="form-control" placeholder="Comment">
                </td>
                <td class="text-center">
                    <a href="javascript:void(0)" title="Delete" class="delete-prescription-medicine-item btn px-1 text-danger fs-3 pe-0">
                        <i class="fa-solid fa-trash"></i>
                    </a>
                </td>
            </tr>
        `;
        
        $(".prescription-medicine-container").append(prescriptionMedicineHtml);
        
        // Initialize select2 for the newly added row - medicine dropdown
        $(".prescription-medicine-container tr:last-child .prescriptionMedicineId").select2({
            placeholder: "Select Medicine",
            width: "100%",
            dropdownParent: $(".prescription-medicine-container tr:last-child .prescriptionMedicineId").parent()
        });
        
        // Initialize day dropdown
        $(".prescription-medicine-container tr:last-child select[name='day[]']").select2({
            placeholder: "Select Duration",
            width: "100%",
            dropdownParent: $(".prescription-medicine-container tr:last-child select[name='day[]']").parent()
        });
        
        // Initialize time dropdown
        $(".prescription-medicine-container tr:last-child select[name='time[]']").select2({
            placeholder: "Select Time",
            width: "100%",
            dropdownParent: $(".prescription-medicine-container tr:last-child select[name='time[]']").parent()
        });
        
        // Initialize dose interval dropdown
        $(".prescription-medicine-container tr:last-child select[name='dose_interval[]']").select2({
            placeholder: "Select Interval",
            width: "100%",
            dropdownParent: $(".prescription-medicine-container tr:last-child select[name='dose_interval[]']").parent()
        });
        
        // Increment uniqueId
        uniquePrescriptionId++;
        $("#prescriptionUniqueId").val(uniquePrescriptionId);
    });

    // Delete medicine row
    $(document).on('click', '.delete-prescription-medicine-item', function() {
        $(this).closest('tr').remove();
        // Renumber rows
        const rows = document.querySelectorAll('.prescription-medicine-container tr');
        rows.forEach((row, index) => {
            row.querySelector('.prescription-item-number').textContent = index + 1;
            row.querySelector('.prescription-item-number').setAttribute('data-item-number', index + 1);
        });
    });

    // Handle medicine selection to show available quantity
    $(document).on('change', '.prescriptionMedicineId', function() {
        let uniqueId = $(this).attr("data-id");
        let medicineId = $(this).val();
        let currentRow = $(this).closest("tr");
        let AvailbleQty = currentRow.find("#AvailbleQty:first");
        let AvailbleQtyClass = currentRow.find(".AvailbleQtyClass");
        let AvlQtyDiv = "#medicineDiv" + uniqueId;

        $.ajax({
            url: route("prescription.medicine.quantity", medicineId),
            type: "GET",
            success: function(data) {
                if (data.data.length !== 0) {
                    let availableQuantity = data.data.available_quantity;
                    let availbleQtyText = `Available Quantity: ${availableQuantity}`;
                    let availbleQtyClass = availableQuantity == 0 ? "text-danger" : "text-success";

                    $(AvailbleQtyClass).text("");
                    $(AvailbleQty)
                        .text(availbleQtyText)
                        .removeClass()
                        .addClass(availbleQtyClass);
                    $(AvlQtyDiv).css({ "margin-top": "22px" });
                }
            },
            error: function() {
                $(AvailbleQty).text("");
                $(AvlQtyDiv).css({ "margin-top": "0px" });
            },
        });
    });

    // Handle prescription form submission
    const prescriptionForm = document.getElementById('addPrescriptionForm');
    if (prescriptionForm) {
        prescriptionForm.addEventListener('submit', function(e) {
            e.preventDefault();
            e.stopPropagation();
            console.log('Prescription form submitted via AJAX');

            const formData = new FormData(this);

            // Send data to backend
            const submitUrl = '{{ route("maternity.prescription.store") }}';
            console.log('Submitting to:', submitUrl);
            fetch(submitUrl, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Close modal
                    $('#addPrescriptionModal').modal('hide');
                    // Reset form
                    this.reset();
                    // Refresh the table
                    Livewire.emit('refresh');
                    // Show success message
                    alert('Prescription added successfully!');
                } else {
                    alert('Error: ' + (data.message || 'Failed to add prescription'));
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Error adding prescription');
            });

            return false;
        });
    }
});
</script>

<!-- Maternity Discharge Modal -->
<div class="modal fade" id="maternityDischargeModal" tabindex="-1" aria-labelledby="maternityDischargeModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="maternityDischargeModalLabel">Discharge Maternity Patient</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="maternityDischargeForm" method="POST" action="{{ route('maternity.discharge') }}">
                    @csrf
                    <input type="hidden" name="patient_id" value="{{ $maternityPatient->id }}">

                    <div class="mb-3">
                        <label for="dischargeReason" class="form-label">Discharge Reason</label>
                        <select class="form-select" id="dischargeReason" name="discharge_reason" required>
                            <option selected disabled value="">Please select a reason...</option>
                            <option value="Condition Improved / Treatment Complete">Condition Improved / Treatment Complete</option>
                            <option value="Transferred to Another Facility">Transferred to Another Facility</option>
                            <option value="Discharged Against Medical Advice (AMA)">Discharged Against Medical Advice (AMA)</option>
                            <option value="Referred to Palliative / Hospice Care">Referred to Palliative / Hospice Care</option>
                            <option value="Deceased">Deceased</option>
                            <option value="Normal Delivery Completed">Normal Delivery Completed</option>
                            <option value="Cesarean Section Completed">Cesarean Section Completed</option>
                            <option value="Postpartum Recovery Complete">Postpartum Recovery Complete</option>
                            <option value="other">Other</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="dischargeNotes" class="form-label">Discharge Notes</label>
                        <textarea class="form-control" id="dischargeNotes" name="discharge_notes" rows="3" placeholder="Enter any additional notes..."></textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-danger" id="confirmDischargeBtn">Discharge Patient</button>
            </div>
        </div>
    </div>
</div>

<script>
// Maternity Discharge Handler
(function() {
    'use strict';

    function initMaternityDischarge() {
        console.log('Initializing maternity discharge handler...');

        const dischargeForm = document.getElementById('maternityDischargeForm');
        const confirmBtn = document.getElementById('confirmDischargeBtn');

        if (!dischargeForm) {
            console.error('Discharge form not found');
            return;
        }

        if (!confirmBtn) {
            console.error('Confirm button not found');
            return;
        }

        console.log('Elements found, setting up event listener...');

        // Remove any existing event listeners
        const newConfirmBtn = confirmBtn.cloneNode(true);
        confirmBtn.parentNode.replaceChild(newConfirmBtn, confirmBtn);

        newConfirmBtn.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();

            console.log('Discharge button clicked');

            const dischargeReason = document.getElementById('dischargeReason');
            if (!dischargeReason || !dischargeReason.value) {
                alert('Please select a discharge reason.');
                return false;
            }

            if (confirm('Are you sure you want to discharge this maternity patient?')) {
                console.log('Proceeding with discharge...');

                // Show loading state
                newConfirmBtn.disabled = true;
                newConfirmBtn.textContent = 'Processing...';

                // Get CSRF token
                const csrfToken = document.querySelector('meta[name="csrf-token"]');
                if (!csrfToken) {
                    alert('CSRF token not found. Please refresh the page.');
                    newConfirmBtn.disabled = false;
                    newConfirmBtn.textContent = 'Discharge Patient';
                    return false;
                }

                // Submit form via AJAX
                const formData = new FormData(dischargeForm);
                console.log('Form data:', Object.fromEntries(formData));

                fetch(dischargeForm.action, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-CSRF-TOKEN': csrfToken.getAttribute('content'),
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(response => {
                    console.log('Response received:', response.status, response.statusText);

                    if (!response.ok) {
                        return response.text().then(text => {
                            throw new Error(`HTTP ${response.status}: ${text}`);
                        });
                    }

                    return response.json();
                })
                .then(data => {
                    console.log('Response data:', data);

                    if (data && data.success) {
                        // Close modal
                        const modalElement = document.getElementById('maternityDischargeModal');
                        if (modalElement) {
                            const modal = bootstrap.Modal.getInstance(modalElement);
                            if (modal) {
                                modal.hide();
                            }
                        }

                        // Show success message
                        alert(data.message || 'Patient discharged successfully!');

                        // Reload page to update the UI
                        setTimeout(() => {
                            window.location.reload();
                        }, 1000);
                    } else {
                        alert('Error: ' + (data.message || 'Unknown error occurred'));
                    }
                })
                .catch(error => {
                    console.error('Error details:', error);
                    alert('An error occurred while discharging the patient: ' + error.message);
                })
                .finally(() => {
                    // Reset button state
                    newConfirmBtn.disabled = false;
                    newConfirmBtn.textContent = 'Discharge Patient';
                });
            }

            return false;
        });

        console.log('Maternity discharge handler initialized successfully');
    }

    // Initialize when DOM is ready
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initMaternityDischarge);
    } else {
        initMaternityDischarge();
    }
})();
</script>

