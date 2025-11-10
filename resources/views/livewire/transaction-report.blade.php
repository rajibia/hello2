<div>
    <style>
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
                            <select class="form-select" wire:model="transactionType">
                                <option value="all">All Types</option>
                                <option value="manual">Manual Payment</option>
                                <option value="online">Online Payment</option>
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
            
            <!-- Card with Table -->
            <div class="card">
                <div class="card-body pb-3 pt-5">
                    <div class="table-responsive">
                        <table class="table table-row-dashed table-row-gray-300 align-middle gs-0 gy-4">
                            <thead>
                                <tr class="border-bottom border-gray-200 text-start text-gray-700 fw-bold fs-7 text-uppercase gs-0">
                                    <th>{{ __('Transaction ID') }}</th>
                                    <th>{{ __('Date') }}</th>
                                    <th>{{ __('Patient') }}</th>
                                    <th>{{ __('Type') }}</th>
                                    <th>{{ __('Payment Method') }}</th>
                                    <th>{{ __('Status') }}</th>
                                    <th class="text-end">{{ __('Amount') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($transactionData['transactions'] as $transaction)
                                    <tr>
                                        <td>
                                            @if($transaction['source'] === 'bill_transaction' && isset($transaction['bill_id']))
                                                <a href="{{ route('bills.show', $transaction['bill_id']) }}" class="text-decoration-none">
                                                    {{ $transaction['transaction_id'] }}
                                                </a>
                                            @else
                                                {{ $transaction['transaction_id'] }}
                                            @endif
                                        </td>
                                        <td>{{ $transaction['date']->format('M d, Y') }}</td>
                                        <td>
                                            @if(isset($transaction['user']))
                                                <div class="d-flex align-items-center">
                                                    <div class="image image-circle image-mini me-3">
                                                        @if(isset($transaction['user']['image']) && $transaction['user']['image'])
                                                            <img src="{{ $transaction['user']['image'] }}" alt="" class="user-img object-contain image rounded-circle">
                                                        @else
                                                            <div class="avatar-circle">
                                                                <span class="initials">{{ substr($transaction['user']['name'] ?? 'NA', 0, 1) }}</span>
                                                            </div>
                                                        @endif
                                                    </div>
                                                    <div class="d-flex flex-column">
                                                        <span class="mb-1">{{ $transaction['user']['name'] }}</span>
                                                        <span>{{ $transaction['user']['email'] }}</span>
                                                    </div>
                                                </div>
                                            @else
                                                {{ __('N/A') }}
                                            @endif
                                        </td>
                                        <td>{{ $transaction['type'] }}</td>
                                        <td>{{ $transaction['payment_type'] }}</td>
                                        <td>
                                            <div class="d-flex align-items-center mt-2">
                                                @php
                                                    $statusClass = 'success';
                                                    if (strtolower($transaction['status']) === 'pending') {
                                                        $statusClass = 'warning';
                                                    } elseif (strtolower($transaction['status']) === 'failed' || strtolower($transaction['status']) === 'rejected') {
                                                        $statusClass = 'danger';
                                                    }
                                                @endphp
                                                <span class="badge bg-light-{{ $statusClass }}">
                                                    {{ $transaction['status'] }}
                                                </span>
                                            </div>
                                        </td>
                                        <td class="text-end">
                                            <span class="fw-bold">
                                                {{ getCurrencySymbol() }} {{ number_format($transaction['amount'], 2) }}
                                            </span>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    
                    <div class="d-flex justify-content-between align-items-center mt-5">
                        <div>
                            @if($transactionData['total'] > $transactionData['perPage'])
                                <nav>
                                    <ul class="pagination">
                                        @for($i = 1; $i <= ceil($transactionData['total'] / $transactionData['perPage']); $i++)
                                            <li class="page-item {{ $i == $transactionData['currentPage'] ? 'active' : '' }}">
                                                <a class="page-link" href="#" wire:click.prevent="$set('page', {{ $i }})">{{ $i }}</a>
                                            </li>
                                        @endfor
                                    </ul>
                                </nav>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- JavaScript for date constraints -->
    <script>
        document.addEventListener('livewire:load', function () {
            // Handle date constraints
            const startDateInput = document.querySelector('input[wire\\:model="startDate"]');
            const endDateInput = document.querySelector('input[wire\\:model="endDate"]');
            
            if (startDateInput && endDateInput) {
                startDateInput.addEventListener('change', function() {
                    // Clear active state from date filter buttons when manually changing date
                    document.querySelectorAll('.btn-group .btn').forEach(btn => {
                        btn.classList.remove('active');
                    });
                    
                    // Set dateFilter to custom
                    @this.set('dateFilter', 'custom');
                    
                    // Handle date constraints
                    if (endDateInput.value && this.value > endDateInput.value) {
                        endDateInput.value = this.value;
                        @this.set('endDate', this.value);
                    }
                });
                
                endDateInput.addEventListener('change', function() {
                    // Clear active state from date filter buttons when manually changing date
                    document.querySelectorAll('.btn-group .btn').forEach(btn => {
                        btn.classList.remove('active');
                    });
                    
                    // Set dateFilter to custom
                    @this.set('dateFilter', 'custom');
                    
                    // Handle date constraints
                    if (startDateInput.value && this.value < startDateInput.value) {
                        startDateInput.value = this.value;
                        @this.set('startDate', this.value);
                    }
                });
            }
            
            // Add event listener for print event
            window.Livewire.on('print-transaction-report', function() {
                $('#printReport').click();
            });
        });
    </script>
    
    <!-- We've moved the print section to the main blade file to avoid duplication -->
</div>
