@extends('layouts.app')
@section('title')
    Revenue Reports
@endsection
@section('content')
    @include('flash::message')
    <div class="container-fluid">
        <div class="d-flex flex-column">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header pb-0">
                            <div class="d-flex justify-content-between align-items-center">
                                <h3>Revenue Reports</h3>
                            </div>
                        </div>
                        <div class="card-body">
                            <!-- Filters -->
                            <form method="GET" action="{{ route('today-payment-reports.index') }}" class="mb-4">
                                <div class="row">
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="type" class="form-label">Revenue Type:</label>
                                            <select name="type" id="type" class="form-select">
                                                <option value="">All Types</option>
                                                @foreach($revenueTypes as $key => $value)
                                                    <option value="{{ $key }}" {{ $filters['type'] == $key ? 'selected' : '' }}>
                                                        {{ $value }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="from_date" class="form-label">From Date:</label>
                                            <input type="date" name="from_date" id="from_date" class="form-control" value="{{ $filters['from_date'] }}">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="to_date" class="form-label">To Date:</label>
                                            <input type="date" name="to_date" id="to_date" class="form-control" value="{{ $filters['to_date'] }}">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label class="form-label">&nbsp;</label>
                                            <div class="d-flex gap-2">
                                                <button type="submit" class="btn btn-primary">
                                                    <i class="fas fa-filter"></i> Filter
                                                </button>
                                                <a href="{{ route('today-payment-reports.index') }}" class="btn btn-secondary">
                                                    <i class="fas fa-times"></i> Clear
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </form>

                            <!-- Revenue Summary -->
                            <div class="row mb-4">
                                <div class="col-md-12">
                                    <div class=" py-3 px-2 alert-info">
                                        <div class="row">
                                            <div class="col-md-4">
                                <strong>Total Records:</strong> {{ $actualTotalCount ?? $revenues->total() }}
                            </div>
                            <div class="col-md-4">
                                <strong>Total Revenue:</strong> {{ formatCurrency($totalRevenue ?? $revenues->sum('amount')) }}
                            </div>
                                            <div class="col-md-4">
                                                <strong>Date Range:</strong> {{ $filters['from_date'] }} to {{ $filters['to_date'] }}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Revenue Table -->
                            <div class="table-responsive">
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th>Reference ID</th>
                                            <th>Patient Name</th>
                                            <th>Company</th>
                                            <th>Revenue Type</th>
                                            <th>Payment Date</th>
                                            <th>Amount</th>
                                            <th>Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($revenues as $revenue)
                                            <tr>
                                                <td>{{ $revenue['reference_id'] }}</td>
                                                <td>{{ $revenue['patient_name'] }}</td>
                                                <td>{{ $revenue['company_name'] }}</td>
                                                <td>
                                                    <span class="badge bg-primary">{{ $revenue['type'] }}</span>
                                                </td>
                                                <td>{{ \Carbon\Carbon::parse($revenue['date'])->format('M d, Y') }}</td>
                                                <td>{{ formatCurrency($revenue['amount']) }}</td>
                                                <td>
                                                    @if($revenue['status'] == 1)
                                                        <span class="badge bg-success">Paid</span>
                                                    @else
                                                        <span class="badge bg-warning">Pending</span>
                                                    @endif
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="7" class="text-center">No revenue records found for the selected criteria.</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>

                            <!-- Pagination -->
                            <div class="d-flex justify-content-center">
                                {{ $revenues->appends(request()->query())->links() }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('page_scripts')
    {{-- assets/js/moment.min.js --}}
@endsection
@section('scripts')
    {{-- assets/js/custom/input_price_format.js --}}
@endsection
