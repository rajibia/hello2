<div>
    <div class="mb-5 mb-xl-10">
        <div class="pt-3">
            <div class="row mb-5">
                <div class="col-lg-7 col-md-12">
                    <!-- Date Filter Buttons -->
                    <div class="d-flex flex-wrap mb-3">
                        <div class="btn-group me-2 mb-2" role="group">
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
                    
                    <!-- Age Filter Buttons -->
                    <div class="d-flex flex-wrap mb-2">
                        <small class="text-muted me-2 align-self-center"><i class="fas fa-birthday-cake me-1"></i>Age Filter:</small>
                        <div class="btn-group me-2" role="group">
                            <button type="button" class="btn btn-outline-primary btn-sm {{ $ageFilter == 'all' ? 'active' : '' }}" 
                                wire:click="changeAgeFilter('all')">
                                <span class="fw-bold">All Ages</span>
                            </button>
                            <button type="button" class="btn btn-outline-primary btn-sm {{ $ageFilter == 'child' ? 'active' : '' }}" 
                                wire:click="changeAgeFilter('child')">
                                <span class="fw-bold">Child (0-12)</span>
                            </button>
                            <button type="button" class="btn btn-outline-primary btn-sm {{ $ageFilter == 'teen' ? 'active' : '' }}" 
                                wire:click="changeAgeFilter('teen')">
                                <span class="fw-bold">Teen (13-19)</span>
                            </button>
                            <button type="button" class="btn btn-outline-primary btn-sm {{ $ageFilter == 'adult' ? 'active' : '' }}" 
                                wire:click="changeAgeFilter('adult')">
                                <span class="fw-bold">Adult (20-59)</span>
                            </button>
                            <button type="button" class="btn btn-outline-primary btn-sm {{ $ageFilter == 'senior' ? 'active' : '' }}" 
                                wire:click="changeAgeFilter('senior')">
                                <span class="fw-bold">Senior (60+)</span>
                            </button>
                        </div>
                    </div>
                </div>
                <div class="col-lg-5 col-md-12">
                    <!-- Date Range Picker -->
                    <div class="d-flex align-items-center mb-3">
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
                                    <i class="fas fa-times me-1"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Custom Age Range Picker -->
                    <div class="d-flex align-items-center">
                        <div class="position-relative w-100">
                            <div class="input-group age-range-picker">
                                <span class="input-group-text bg-primary">
                                    <i class="fas fa-birthday-cake text-white"></i>
                                </span>
                                <input type="number" class="form-control" placeholder="Min Age" id="minAge"
                                    wire:model="minAge" min="0" max="120">
                                <span class="input-group-text border-start-0 border-end-0 rounded-0">to</span>
                                <input type="number" class="form-control" placeholder="Max Age" id="maxAge"
                                    wire:model="maxAge" min="0" max="120">
                                <button type="button" class="btn btn-light-secondary" wire:click="changeAgeFilter('all')">
                                    <i class="fas fa-times me-1"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row g-5 g-xl-8">
                <!-- OPD Statistics -->
                <div class="col-xl-6">
                    <div class="card card-xl-stretch mb-xl-8 shadow-sm hover-elevate-up">
                        <div class="card-header border-0 bg-light-info pt-5">
                            <div class="card-title">
                                <div class="d-flex align-items-center">
                                    <div class="symbol symbol-45px me-5">
                                        <i class="fas fa-user-md fs-1 text-info"></i>
                                    </div>
                                    <div class="d-flex flex-column">
                                        <h4 class="mb-1 text-dark">OPD Statistics</h4>
                                        <p class="text-muted mb-0" id="dateRangeDisplay">{{ $startDate }} - {{ $endDate }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-body py-4">
                            <div class="mb-6">
                                <div class="d-flex align-items-center bg-light-info rounded p-5 mb-7">
                                    <span class="svg-icon svg-icon-info me-5">
                                        <i class="fas fa-users text-info"></i>
                                    </span>
                                    <div class="flex-grow-1 me-2">
                                        <span class="text-muted fw-bold d-block">Total Patients</span>
                                        <span class="text-info fw-bolder fs-1">{{ $opdTotal }}</span>
                                    </div>
                                </div>
                            </div>

                            <div class="row g-5">
                                <div class="col-6">
                                    <div class="d-flex align-items-center bg-light-success rounded p-3 mb-3">
                                        <span class="svg-icon svg-icon-success me-3">
                                            <i class="fas fa-user-plus text-success"></i>
                                        </span>
                                        <div class="flex-grow-1 me-2">
                                            <span class="text-muted fw-bold d-block">New</span>
                                            <span class="text-success fw-bolder fs-2">{{ $opdNew }}</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="d-flex align-items-center bg-light-primary rounded p-3 mb-3">
                                        <span class="svg-icon svg-icon-primary me-3">
                                            <i class="fas fa-user-check text-primary"></i>
                                        </span>
                                        <div class="flex-grow-1 me-2">
                                            <span class="text-muted fw-bold d-block">Old</span>
                                            <span class="text-primary fw-bolder fs-2">{{ $opdOld }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="separator separator-dashed my-5"></div>

                            <div class="row g-5">
                                <div class="col-6">
                                    <div class="d-flex align-items-center bg-light-warning rounded p-3">
                                        <span class="svg-icon svg-icon-warning me-3">
                                            <i class="fas fa-male text-warning"></i>
                                        </span>
                                        <div class="flex-grow-1 me-2">
                                            <span class="text-muted fw-bold d-block">Male</span>
                                            <span class="text-warning fw-bolder fs-2">{{ $opdMale }}</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="d-flex align-items-center bg-light-danger rounded p-3">
                                        <span class="svg-icon svg-icon-danger me-3">
                                            <i class="fas fa-female text-danger"></i>
                                        </span>
                                        <div class="flex-grow-1 me-2">
                                            <span class="text-muted fw-bold d-block">Female</span>
                                            <span class="text-danger fw-bolder fs-2">{{ $opdFemale }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- IPD Statistics -->
                <div class="col-xl-6">
                    <div class="card card-xl-stretch mb-xl-8 shadow-sm hover-elevate-up">
                        <div class="card-header border-0 bg-light-primary pt-5">
                            <div class="card-title">
                                <div class="d-flex align-items-center">
                                    <div class="symbol symbol-45px me-5">
                                        <i class="fas fa-procedures fs-1 text-primary"></i>
                                    </div>
                                    <div class="d-flex flex-column">
                                        <h4 class="mb-1 text-primary">IPD Statistics</h4>
                                        <p class="text-muted mb-0">{{ $startDate }} - {{ $endDate }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-body py-4">
                            <div class="mb-6">
                                <div class="d-flex align-items-center bg-light-primary rounded p-5 mb-7">
                                    <span class="svg-icon svg-icon-primary me-5">
                                        <i class="fas fa-users text-primary"></i>
                                    </span>
                                    <div class="flex-grow-1 me-2">
                                        <span class="text-muted fw-bold d-block">Total Patients</span>
                                        <span class="text-primary fw-bolder fs-1">{{ $ipdTotal }}</span>
                                    </div>
                                </div>
                            </div>

                            <div class="row g-5">
                                <div class="col-6">
                                    <div class="d-flex align-items-center bg-light-success rounded p-3 mb-3">
                                        <span class="svg-icon svg-icon-success me-3">
                                            <i class="fas fa-user-plus text-success"></i>
                                        </span>
                                        <div class="flex-grow-1 me-2">
                                            <span class="text-muted fw-bold d-block">New</span>
                                            <span class="text-success fw-bolder fs-2">{{ $ipdNew }}</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="d-flex align-items-center bg-light-info rounded p-3 mb-3">
                                        <span class="svg-icon svg-icon-info me-3">
                                            <i class="fas fa-user-check text-info"></i>
                                        </span>
                                        <div class="flex-grow-1 me-2">
                                            <span class="text-muted fw-bold d-block">Old</span>
                                            <span class="text-info fw-bolder fs-2">{{ $ipdOld }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="separator separator-dashed my-5"></div>

                            <div class="row g-5">
                                <div class="col-6">
                                    <div class="d-flex align-items-center bg-light-warning rounded p-3">
                                        <span class="svg-icon svg-icon-warning me-3">
                                            <i class="fas fa-male text-warning"></i>
                                        </span>
                                        <div class="flex-grow-1 me-2">
                                            <span class="text-muted fw-bold d-block">Male</span>
                                            <span class="text-warning fw-bolder fs-2">{{ $ipdMale }}</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="d-flex align-items-center bg-light-danger rounded p-3">
                                        <span class="svg-icon svg-icon-danger me-3">
                                            <i class="fas fa-female text-danger"></i>
                                        </span>
                                        <div class="flex-grow-1 me-2">
                                            <span class="text-muted fw-bold d-block">Female</span>
                                            <span class="text-danger fw-bolder fs-2">{{ $ipdFemale }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Report content ends here -->
</div>

@push('scripts')
<script>
    // Function to format date as YYYY-MM-DD
    function formatDate(date) {
        const d = new Date(date);
        const year = d.getFullYear();
        const month = String(d.getMonth() + 1).padStart(2, '0');
        const day = String(d.getDate()).padStart(2, '0');
        return `${year}-${month}-${day}`;
    }
    
    // Function to format date for display (more readable format)
    function formatDateForDisplay(dateStr) {
        const d = new Date(dateStr);
        const options = { year: 'numeric', month: 'short', day: 'numeric' };
        return d.toLocaleDateString('en-US', options);
    }
    
    // Function to apply date filter
    function applyDateFilter() {
        const startDateInput = document.getElementById('startDate');
        const endDateInput = document.getElementById('endDate');
        
        const startDate = startDateInput.value;
        const endDate = endDateInput.value;
        
        if (startDate && endDate) {
            // Explicitly set the values to ensure they're updated
            @this.set('startDate', startDate);
            @this.set('endDate', endDate);
            @this.set('dateFilter', 'custom');
            
            // Force a refresh of the data
            @this.call('loadCounts');
            
            // Format dates for display
            const formattedStartDate = formatDateForDisplay(startDate);
            const formattedEndDate = formatDateForDisplay(endDate);
            
            // Update the date display
            $('#dateRangeDisplay').text(formattedStartDate + ' - ' + formattedEndDate);
            
            // Remove active class from all buttons
            $('.btn-group .btn').removeClass('active');
            
            console.log('Filter applied with date range:', startDate, 'to', endDate);
        }
    }
    
    document.addEventListener('livewire:load', function () {
        // Set up date constraints
        const startDateInput = document.getElementById('startDate');
        const endDateInput = document.getElementById('endDate');
        
        // Set today as max date
        const today = new Date().toISOString().split('T')[0];
        startDateInput.max = today;
        endDateInput.max = today;
        
        // Update min/max constraints when dates change
        startDateInput.addEventListener('change', function() {
            // Set minimum date for end date
            endDateInput.min = this.value;
            
            // If end date is now before start date, update it
            if (endDateInput.value && endDateInput.value < this.value) {
                endDateInput.value = this.value;
                @this.set('endDate', this.value);
            }
            
            @this.set('startDate', this.value);
            
            // Apply filter if both dates are selected
            if (this.value && endDateInput.value) {
                applyDateFilter();
            }
        });
        
        endDateInput.addEventListener('change', function() {
            // Set maximum date for start date
            startDateInput.max = this.value || today;
            
            // If start date is now after end date, update it
            if (startDateInput.value && startDateInput.value > this.value) {
                startDateInput.value = this.value;
                @this.set('startDate', this.value);
            }
            
            @this.set('endDate', this.value);
            
            // Apply filter if both dates are selected
            if (this.value && startDateInput.value) {
                applyDateFilter();
            }
        });
        
        // Format the initial date display
        if (startDateInput.value && endDateInput.value) {
            const formattedStartDate = formatDateForDisplay(startDateInput.value);
            const formattedEndDate = formatDateForDisplay(endDateInput.value);
            $('#dateRangeDisplay').text(formattedStartDate + ' - ' + formattedEndDate);
        }
        
        // Handle clear dates button click
        $('#clearDates').click(function() {
            // Reset to today's date
            startDateInput.value = today;
            endDateInput.value = today;
            
            // Reset min/max constraints
            startDateInput.max = today;
            endDateInput.min = '';
            
            // Update Livewire component
            @this.set('startDate', today);
            @this.set('endDate', today);
            @this.set('dateFilter', 'today');
            
            // Load counts with today's date
            @this.call('loadCounts');
            
            // Update the date display with formatted date
            const formattedToday = formatDateForDisplay(today);
            $('#dateRangeDisplay').text(formattedToday + ' - ' + formattedToday);
            
            // Update active button state
            $('.btn-group .btn').removeClass('active');
            $('.btn-group .btn[wire:click="changeDateFilter(\'today\')"]').addClass('active');
            
            console.log('Reset to today:', today);
        });
        
        // Add hover effect to cards
        $('.hover-elevate-up').hover(
            function() { $(this).addClass('shadow'); },
            function() { $(this).removeClass('shadow'); }
        );
        
        // Add click handlers for date filter buttons
        $('.btn-group .btn').click(function() {
            // Remove active class from all buttons
            $('.btn-group .btn').removeClass('active');
            // Add active class to clicked button
            $(this).addClass('active');
        });
    });
</script>
@endpush
