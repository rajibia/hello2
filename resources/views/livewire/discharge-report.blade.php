<div>
    <style>
        .nav-tabs .nav-item .nav-link:after {
            border-bottom: 0 !important;
        }
        table th {
            padding: 0.5rem !important;
        }
    </style>
    <div class="mb-5 mb-xl-10">
        <div class="pt-3">
            <div class="row mb-5">
                <div class="col-lg-4 col-md-12">
                    <div class="d-flex flex-wrap mb-5">
                        <div class="btn-group me-5 mb-2" role="group">
                            <button type="button" class="btn btn-outline-primary btn-lg py-3 {{ $dateFilter == 'today' ? 'active' : '' }}" 
                                wire:click="changeDateFilter('today')">
                                <span class="fw-bold">Today</span>
                            </button>
                            <button type="button" class="btn btn-outline-primary btn-lg py-3 {{ $dateFilter == 'yesterday' ? 'active' : '' }}" 
                                wire:click="changeDateFilter('yesterday')">
                                <span class="fw-bold">Yesterday</span>
                            </button>
                            <button type="button" class="btn btn-outline-primary btn-lg py-3 {{ $dateFilter == 'this_week' ? 'active' : '' }}" 
                                wire:click="changeDateFilter('this_week')">
                                <span class="fw-bold">This Week</span>
                            </button>
                            <button type="button" class="btn btn-outline-primary btn-lg py-3 {{ $dateFilter == 'this_month' ? 'active' : '' }}" 
                                wire:click="changeDateFilter('this_month')">
                                <span class="fw-bold">This Month</span>
                            </button>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-12">
                    <div class="d-flex align-items-center justify-content-between">
                        <div class="input-group" style="max-width: 300px;">
                            <span class="input-group-text bg-primary text-white">
                                <i class="fas fa-filter"></i>
                            </span>
                            <select class="form-select" wire:model="dischargeStatus" wire:change="loadDischarges">
                                <option value="discharged">{{ $activeTab === 'opd' ? 'Completed' : 'Discharged' }}</option>
                                <option value="partial">{{ $activeTab === 'opd' ? 'In Progress' : 'Partially Discharged' }}</option>
                                <option value="all">All</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="col-lg-5 col-md-12">
                    <div class="d-flex align-items-center">
                        <div class="position-relative w-100">
                            <div class="input-group date-range-picker">
                                <span class="input-group-text bg-primary">
                                    <i class="fas fa-calendar-alt text-white"></i>
                                </span>
                                <input type="date" class="form-control" placeholder="Start Date" id="startDate"
                                    wire:model="startDate" max="{{ date('Y-m-d') }}">
                                <span class="input-group-text border-start-0 border-end-0 rounded-0">to</span>
                                <input type="date" class="form-control" placeholder="End Date" id="endDate"
                                    wire:model="endDate" max="{{ date('Y-m-d') }}">
                                <button type="button" class="btn btn-light-secondary" wire:click="changeDateFilter('today')">
                                    <i class="fas fa-times"></i> Clear
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                
                <h5 class="text-muted fw-normal mb-0 date-range-display">
                    <i class="fas fa-calendar-alt me-1"></i> 
                    {{ $formattedStartDate }} - {{ $formattedEndDate }}
                </h5>
            </div>
            
            <!-- Tabs and Content in Single Card -->
            <div class="card">
                <div class="card-header border-0 pt-5 pb-3">
                    <!-- Tabs Navigation -->
                    <div class="card-title">
                        <ul class="nav nav-tabs nav-line-tabs nav-line-tabs-2x fs-3 fw-bolder" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link {{ $activeTab === 'opd' ? 'active' : '' }}" 
                                   wire:click="changeTab('opd')" 
                                   data-bs-toggle="tab" 
                                   href="#opd-tab">
                                    <span class="d-flex align-items-center">
                                        <i class="fas fa-user-md me-2"></i>OPD Discharges
                                    </span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ $activeTab === 'ipd' ? 'active' : '' }}" 
                                   wire:click="changeTab('ipd')" 
                                   data-bs-toggle="tab" 
                                   href="#ipd-tab">
                                    <span class="d-flex align-items-center">
                                        <i class="fas fa-procedures me-2"></i>IPD Discharges
                                    </span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
                
                <div class="card-body pb-3 pt-0">
                    <!-- Tab Content -->
                    <div class="tab-content">
                        <!-- OPD Tab -->
                        <div class="tab-pane fade {{ $activeTab === 'opd' ? 'show active' : '' }}" id="opd-tab">
                            <div class="table-responsive">
                                <table class="table table-row-dashed table-row-gray-300 align-middle gs-0 gy-4">
                                    <thead>
                                        <tr class="border-bottom border-gray-200 text-start text-gray-700 fw-bold fs-7 text-uppercase gs-0">
                                            <th>Patient</th>
                                            <th>OPD Number</th>
                                            <th>Doctor</th>
                                            <th>Appointment Date</th>
                                            <th>Served Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($opdDischarges as $opd)
                                            <tr>
                                                <td>
                                                <div class="d-flex align-items-center">
                                                    <div class="image image-circle image-mini me-3">
                                                        <a href="{{ route('patients.show',$opd->patient->id) }}">
                                                            <img src="{{ $opd->patient->patientUser->image_url }}" alt=""
                                                                class="user-img object-contain image rounded-circle">
                                                        </a>
                                                    </div>
                                                    <div class="d-flex flex-column">
                                                        <a href="{{ route('patients.show',$opd->patient->id) }}"
                                                        class="text-decoration-none mb-1">{{ $opd->patient->patientUser->full_name }}</a>
                                                        <span>{{ $opd->patient->patientUser->email }}</span>
                                                    </div>
                                                </div>
                                                </td>
                                                <td>
                                                    <div class="d-flex align-items-center mt-2">
                                                        <a href="{{ route('opd.patient.show',$opd->id) }}" class="badge bg-light-info text-decoration-none">{{ $opd->opd_number  }}</a>    
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="d-flex align-items-center">
                                                        <div class="image image-circle image-mini me-3">
                                                            <a href="{{url('doctors') . '/' . $opd->doctor->id}}">
                                                                <img src="{{$opd->doctor->doctorUser->image_url}}" alt=""
                                                                    class="user-img object-contain image rounded-circle" height="35" width="35">
                                                            </a>
                                                        </div>
                                                        <div class="d-flex flex-column">
                                                            <a href="{{ url('doctors') . '/' . $opd->doctor->id }}"
                                                            class="text-decoration-none mb-1">{{$opd->doctor->doctorUser->full_name}}</a>
                                                            <span>{{$opd->doctor->doctorUser->email}}</span>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    @if ($opd->appointment_date === null)
                                                        {{ __('messages.common.n/a') }}
                                                    @endif
                                                    <div class="badge bg-light-info">
                                                        <div class="mb-2">{{ \Carbon\Carbon::parse($opd->appointment_date)->format('h:i A') }}
                                                        </div>
                                                        <div>
                                                            {{ \Carbon\Carbon::parse($opd->appointment_date)->isoFormat('Do MMMM YYYY') }}
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="d-flex align-items-center mt-2">
                                                        @if ($opd->served == 0)
                                                            <span class="badge bg-light-primary">Not Served</span>
                                                        @elseif ($opd->served == 1)
                                                            <span class="badge bg-light-success">Served</span>
                                                        @endif    
                                                    </div>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="6" class="text-center">No discharge records found</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                            
                            <div class="d-flex justify-content-between align-items-center mt-5">
                                <div>
                                    {{ $opdDischarges->onEachSide(1)->links() }}
                                </div>
                            </div>
                        </div>
                        
                        <!-- IPD Tab -->
                        <div class="tab-pane fade {{ $activeTab === 'ipd' ? 'show active' : '' }}" id="ipd-tab">
                            <div class="table-responsive">
                                <table class="table table-row-dashed table-row-gray-300 align-middle gs-0 gy-4">
                                    <thead>
                                        <tr class="border-bottom border-gray-200 text-start text-gray-700 fw-bold fs-7 text-uppercase gs-0">
                                            <th>Patient</th>
                                            <th>IPD Number</th>
                                            <th>Doctor</th>
                                            <th>Admission Date</th>
                                            <th>Discharge Date</th>
                                            <th>Discharge Status</th>
                                            <th>Bill Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($ipdDischarges as $ipd)
                                            <tr>
                                                <td>
                                                    <div class="d-flex align-items-center">
                                                        <div class="image image-circle image-mini me-3">
                                                            <a href="{{ route('patients.show',$ipd->patient->id) }}">
                                                                <img src="{{ $ipd->patient->patientUser->image_url }}" alt=""
                                                                    class="user-img object-contain image rounded-circle">
                                                            </a>
                                                        </div>
                                                        <div class="d-flex flex-column">
                                                            <a href="{{ route('patients.show',$ipd->patient->id) }}"
                                                                class="text-decoration-none mb-1">{{ $ipd->patient->patientUser->full_name }}</a>
                                                            <span>{{ $ipd->patient->patientUser->email }}</span>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="d-flex align-items-center mt-2">
                                                        <a href="{{ route('ipd.patient.show',$ipd->id) }}" class="badge bg-light-info text-decoration-none">{{ $ipd->ipd_number }}</a>    
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="d-flex align-items-center">
                                                        <div class="image image-circle image-mini me-3">
                                                            <a href="{{ url('doctors') . '/' . $ipd->doctor->id }}">
                                                                <img src="{{$ipd->doctor->doctorUser->image_url}}" alt=""
                                                                    class="user-img object-contain image rounded-circle">
                                                            </a>
                                                        </div>
                                                        <div class="d-flex flex-column">
                                                            <a href="{{ url('doctors') . '/' . $ipd->doctor->id }}"
                                                            class="text-decoration-none mb-1">{{$ipd->doctor->doctorUser->full_name}}</a>
                                                            <span>{{ $ipd->doctor->doctorUser->email}}</span>
                                                        </div>
                                                    </div>
                                                </td>   
                                                <td>
                                                @if ($ipd->admission_date === null)
                                                    {{ __('messages.common.n/a') }}
                                                @else
                                                <div class="badge bg-light-info">
                                                    <div class="mb-2">{{ \Carbon\Carbon::parse($ipd->admission_date)->format('h:i A')}}
                                                    </div>
                                                    <div>
                                                        {{ \Carbon\Carbon::parse($ipd->admission_date)->translatedFormat('jS M,Y')}}
                                                    </div>
                                                </div>
                                                @endif
                                                </td>
                                                <td>
                                                    @if ($ipd->discharge_date === null)
                                                        {{ __('messages.common.n/a') }}
                                                    @else
                                                    <div class="badge bg-light-info">
                                                        <div class="mb-2">{{ \Carbon\Carbon::parse($ipd->discharge_date)->format('h:i A')}}
                                                        </div>
                                                        <div>
                                                            {{ \Carbon\Carbon::parse($ipd->discharge_date)->translatedFormat('jS M,Y')}}
                                                        </div>
                                                    </div>
                                                    @endif
                                                </td>
                                                <td>
                                                    <div class="d-flex align-items-center mt-2">
                                                        @if ($ipd->bill_status == 1)
                                                            <span class="badge bg-light-success">Discharged</span>
                                                        @elseif ($ipd->doctor_discharge == 1)
                                                            <span class="badge bg-light-warning">Partial Discharged</span>
                                                        @else
                                                            <span class="badge bg-light-danger">Not Discharged</span>
                                                        @endif
                                                    </div>
                                                </td>
                                                <td>
                                                <div class="d-flex align-items-center mt-2">
                                                    @if ($ipd->bill_status == 1 && $ipd->bill)
                                                        @if ($ipd->bill->net_payable_amount <= 0)
                                                            <span class="badge bg-light-success">{{ __('messages.employee_payroll.paid') }}</span>
                                                        @else
                                                            <span class="badge bg-light-danger">{{ __('messages.employee_payroll.not_paid') }}</span>
                                                        @endif
                                                    @else
                                                        <span class="badge bg-light-danger">{{ __('messages.employee_payroll.not_paid') }}</span>
                                                    @endif
                                                </div>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="7" class="text-center">No discharge records found</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                            
                            <div class="d-flex justify-content-between align-items-center mt-5">
                                <div>
                                    {{ $ipdDischarges->links() }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Print Sections -->
    <div class="d-none" id="opdPrintSection">
        <div class="print-header">
            <h1>{{env('APP_NAME')}}</h1>
            <h2>OPD Discharge Report</h2>
            <h4>{{ $formattedStartDate }} - {{ $formattedEndDate }}</h4>
            <h5>Status: {{ ucfirst($dischargeStatus) }}</h5>
        </div>
        
        <table class="print-table">
            <thead>
                <tr>
                    <th>Patient</th>
                    <th>OPD Number</th>
                    <th>Doctor</th>
                    <th>Appointment Date</th>
                    <th>Service Status</th>
                    <th>Payment Mode</th>
                </tr>
            </thead>
            <tbody>
                @forelse($opdDischarges as $opd)
                    <tr>
                        <td>{{ $opd->first_name }} {{ $opd->last_name }}</td>
                        <td>{{ $opd->opd_number }}</td>
                        <td>{{ $opd->doctor_first_name }} {{ $opd->doctor_last_name }}</td>
                        <td>{{ $this->formatDate($opd->appointment_date) }}</td>
                        <td>{{ $opd->served == 1 ? 'Completed' : 'In Progress' }}</td>
                        <td>{{ isset($opd->payment_mode) ? $opd->payment_mode : 'Not Specified' }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center">No OPD discharge records found for the selected period.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    <div class="d-none" id="ipdPrintSection">
        <div class="print-header">
            <h1>{{env('APP_NAME')}}</h1>
            <h2>IPD Discharge Report</h2>
            <h4>{{ $formattedStartDate }} - {{ $formattedEndDate }}</h4>
            <h5>Status: {{ ucfirst($dischargeStatus) }}</h5>
        </div>
        
        <table class="print-table">
            <thead>
                <tr>
                    <th>Patient</th>
                    <th>IPD Number</th>
                    <th>Doctor</th>
                    <th>Admission Date</th>
                    <th>Discharge Date</th>
                    <th>Length of Stay</th>
                    <th>Discharge Status</th>
                    <th>Bill Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse($ipdDischarges as $ipd)
                    <tr>
                        <td>{{ $ipd->first_name }} {{ $ipd->last_name }}</td>
                        <td>{{ $ipd->ipd_number }}</td>
                        <td>{{ $ipd->doctor_first_name }} {{ $ipd->doctor_last_name }}</td>
                        <td>{{ $this->formatDate($ipd->admission_date) }}</td>
                        <td>{{ $ipd->discharge_date ? $this->formatDate($ipd->discharge_date) : 'Not Discharged' }}</td>
                        <td>{{ $this->calculateLengthOfStay($ipd->admission_date, $ipd->discharge_date) }}</td>
                        <td>{{ $ipd->doctor_discharge == 1 ? 'Doctor Approved' : 'Pending' }}</td>
                        <td>{{ $ipd->bill_status == 1 ? 'Paid' : 'Unpaid' }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="text-center">No IPD discharge records found for the selected period.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    <script>
        document.addEventListener('livewire:load', function () {
            // Handle date constraints
            const startDateInput = document.querySelector('input[wire\\:model="startDate"]');
            const endDateInput = document.querySelector('input[wire\\:model="endDate"]');
            
            if (startDateInput && endDateInput) {
                startDateInput.addEventListener('change', function() {
                    if (endDateInput.value && this.value > endDateInput.value) {
                        endDateInput.value = this.value;
                        @this.set('endDate', this.value);
                    }
                });
                
                endDateInput.addEventListener('change', function() {
                    if (startDateInput.value && this.value < startDateInput.value) {
                        startDateInput.value = this.value;
                        @this.set('startDate', this.value);
                    }
                });
            }
            
            // Add event listeners for print events
            window.Livewire.on('print-opd-report', function() {
                const printContent = document.getElementById('opdPrintSection').innerHTML;
                printReport(printContent);
            });
            
            window.Livewire.on('print-ipd-report', function() {
                const printContent = document.getElementById('ipdPrintSection').innerHTML;
                printReport(printContent);
            });
        });
        
        function printReport(printContent) {
            const originalContent = document.body.innerHTML;
            
            document.body.innerHTML = `
                <div class="print-container">
                    ${printContent}
                </div>
                <style>
                    @media print {
                        body {
                            font-family: Arial, sans-serif;
                            padding: 20px;
                            max-width: 1000px; 
                            margin: 0 auto;
                        }
                        .no-print {
                            text-align: center;
                        }
                        .print-header { 
                            text-align: center; 
                            margin-bottom: 30px; 
                        }
                        .print-header h2 { 
                            margin-bottom: 5px; 
                        }
                        .print-header h4 { 
                            margin-bottom: 5px; 
                            font-weight: normal; 
                        }
                        .print-header h5 { 
                            margin-bottom: 5px; 
                            font-weight: normal; 
                        }
                        .print-table {
                            width: 100%;
                            border-collapse: collapse;
                            margin-top: 20px;
                        }
                        .print-table th, .print-table td {
                            border: 1px solid #ddd;
                            padding: 8px;
                            text-align: left;
                        }
                        .print-table th {
                            background-color: #f2f2f2;
                        }
                    }
                </style>
            `;
            
            window.print();
            document.body.innerHTML = originalContent;
            window.location.reload();
        }
    </script>
</div>
