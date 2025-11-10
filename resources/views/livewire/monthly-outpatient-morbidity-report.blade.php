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
                <div class="col-lg-6 col-md-12">
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
                <div class="col-lg-6 col-md-12">
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
                                <button type="button" class="btn btn-light-secondary" wire:click="changeDateFilter('this_month')">
                                    <i class="fas fa-times"></i> Clear
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <h5 class="text-muted fw-normal mb-4 mt-3 date-range-display">
                <i class="fas fa-calendar-alt me-1"></i> 
                {{ $formattedStartDate }} - {{ $formattedEndDate }}
            </h5>

            <!-- Summary Stats Cards -->
            <div class="row g-5 g-xl-8 mb-5">
                <div class="col-xl-6">
                    <div class="card bg-light-primary">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="d-flex flex-center h-60px w-60px rounded bg-primary p-2">
                                    <i class="fas fa-users fa-3x text-white"></i>
                                </div>
                                <div class="ms-5">
                                    <div class="fs-4 text-gray-800 fw-bold">{{ $totalPatients }}</div>
                                    <div class="fs-6 text-gray-600">Total Patients</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-6">
                    <div class="card bg-light-success">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="d-flex flex-center h-60px w-60px rounded bg-success p-2">
                                    <i class="fas fa-stethoscope fa-3x text-white"></i>
                                </div>
                                <div class="ms-5">
                                    <div class="fs-4 text-gray-800 fw-bold">{{ $totalDiagnoses }}</div>
                                    <div class="fs-6 text-gray-600">Total Diagnoses</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Main Table Card -->
            <div class="card">
                <div class="card-body pb-3 pt-5">
                    <div class="table-responsive">
                        <table class="table table-row-dashed table-row-gray-300 align-middle gs-0 gy-4">
                            <thead>
                                <tr class="fw-bolder text-muted">
                                    <th class="min-w-150px">Category</th>
                                    <th class="min-w-100px">Code</th>
                                    <th class="min-w-100px text-center">Patient Count</th>
                                    <th class="min-w-100px text-center">Diagnosis Count</th>
                                    <th class="min-w-100px text-center">% of Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($morbidityData as $data)
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="d-flex flex-column">
                                                    <span class="text-gray-800 text-hover-primary mb-1">{{ $data->category_name }}</span>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="badge bg-light-info">{{ $data->category_code }}</span>
                                        </td>
                                        <td class="text-center">
                                            <span class="text-dark fw-bolder fs-6">{{ $data->patient_count }}</span>
                                        </td>
                                        <td class="text-center">
                                            <span class="text-dark fw-bolder fs-6">{{ $data->diagnosis_count }}</span>
                                        </td>
                                        <td class="text-center">
                                            @if($totalDiagnoses > 0)
                                                <span class="text-dark fw-bolder fs-6">
                                                    {{ number_format(($data->diagnosis_count / $totalDiagnoses) * 100, 1) }}%
                                                </span>
                                            @else
                                                <span class="text-dark fw-bolder fs-6">0%</span>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center">No morbidity records found.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    
                    <div class="d-flex justify-content-between align-items-center mt-5">
                        <div>
                            {{ $morbidityData->onEachSide(1)->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Print Section (Hidden) -->
    <div class="d-none" id="monthlyMorbidityPrintSection">
        <table class="print-table">
            <thead>
                <tr>
                    <th>Category</th>
                    <th>Code</th>
                    <th>Patient Count</th>
                    <th>Diagnosis Count</th>
                    <th>% of Total</th>
                </tr>
            </thead>
            <tbody>
                @forelse($morbidityData as $data)
                    <tr>
                        <td>{{ $data->category_name }}</td>
                        <td>{{ $data->category_code }}</td>
                        <td>{{ $data->patient_count }}</td>
                        <td>{{ $data->diagnosis_count }}</td>
                        <td>
                            @if($totalDiagnoses > 0)
                                {{ number_format(($data->diagnosis_count / $totalDiagnoses) * 100, 1) }}%
                            @else
                                0%
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center">No morbidity records found.</td>
                    </tr>
                @endforelse
            </tbody>
            <tfoot>
                <tr>
                    <th colspan="2">Totals</th>
                    <th>{{ $totalPatients }}</th>
                    <th>{{ $totalDiagnoses }}</th>
                    <th>100%</th>
                </tr>
            </tfoot>
        </table>
        
        <!-- Print Buttons -->
        <div class="no-print" style="display: flex; justify-content: center; margin-top: 30px; margin-bottom: 20px;">
            <button class="btn btn-primary me-2" onclick="window.print();" style="display: inline-block !important;">Print Report</button>
            <button class="btn btn-secondary" onclick="window.close();" style="display: inline-block !important;">Close</button>
        </div>
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
        });
    </script>
</div>
