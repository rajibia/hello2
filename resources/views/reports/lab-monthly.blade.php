@extends('layouts.app')

@section('title', 'Laboratory Monthly Report')

@section('content')
<div class="d-flex flex-column h-100">
    {{-- ==================== HEADER ==================== --}}
    <div class="bg-light border-bottom px-4 py-3">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
            <h4 class="mb-0 text-primary fw-bold d-flex align-items-center">
                <i class="fas fa-flask me-2"></i>Laboratory Monthly Report
            </h4>

            <div class="d-flex gap-2 flex-wrap">
                <button onclick="window.print()" class="btn btn-outline-secondary btn-sm d-flex align-items-center gap-1">
                    Print
                </button>
                <button id="pdfBtn" class="btn btn-danger btn-sm d-flex align-items-center gap-1">
                    PDF
                </button>
                <button id="excelBtn" class="btn btn-success btn-sm d-flex align-items-center gap-1">
                    Excel
                </button>
                <button id="csvBtn" class="btn btn-info btn-sm text-white d-flex align-items-center gap-1">
                    CSV
                </button>
                <a href="{{ route('reports.index') }}" class="btn btn-light btn-sm d-flex align-items-center gap-1">
                    Back
                </a>
            </div>
        </div>
    </div>

    {{-- ==================== MAIN CONTENT ==================== --}}
    <div class="flex-grow-1 p-4 bg-white overflow-auto">
        <!-- Month Picker -->
        <form method="GET" class="row g-3 mb-4 align-items-end">
            @csrf
            <div class="col-md-3 col-sm-6">
                <label class="form-label fw-semibold text-muted">Select Month</label>
                <input type="month" name="month" class="form-control form-control-sm"
                       value="{{ request('month', now()->format('Y-m')) }}">
            </div>
            <div class="col-md-2 col-sm-3">
                <button type="submit" class="btn btn-primary btn-sm w-100">
                    Go
                </button>
            </div>
        </form>

        @php
            $monthInput = request('month', now()->format('Y-m'));
            $startDate = \Carbon\Carbon::createFromFormat('Y-m', $monthInput)->startOfMonth();
            $endDate   = $startDate->copy()->endOfMonth();

            $year      = $startDate->format('Y');
            $monthName = $startDate->format('F');

            $results = DB::select("
                SELECT 
                    COALESCE(company, 'Walk-in') AS company_name,
                    frequency
                FROM lab_visits
                WHERE visit_date BETWEEN ? AND ?
                ORDER BY frequency DESC
            ", [$startDate->format('Y-m-d'), $endDate->format('Y-m-d')]);

            $total = array_sum(array_column($results, 'frequency'));
        @endphp

        <!-- Report Title -->
        <div class="text-center mb-4">
            <h5 class="fw-bold text-uppercase text-primary">
                Attendance Report — {{ $monthName }} {{ $year }}
            </h5>
        </div>

        <!-- Table (80%) + Chart (20%) -->
        <div class="row g-4">
            <!-- TABLE: FULL WIDTH (80%) -->
            <div class="col-lg-8">
                <div class="table-responsive h-100 d-flex flex-column">
                    <table class="table table-bordered table-hover align-middle mb-0 w-100" id="attendanceTable">
                        <thead class="table-primary text-white sticky-top">
                            <tr>
                                <th width="8%" class="text-center">S/N</th>
                                <th>COMPANY</th>
                                <th width="18%" class="text-center">FREQUENCY</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($results as $index => $row)
                                <tr>
                                    <td class="text-center fw-semibold">{{ $loop->iteration }}</td>
                                    <td>{{ $row->company_name }}</td>
                                    <td class="text-center">{{ $row->frequency }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="text-center text-muted py-5">
                                        No visits recorded for {{ $monthName }} {{ $year }}
                                    </td>
                                </tr>
                            @endforelse
                            <tr class="table-secondary fw-bold">
                                <td colspan="2" class="text-end pe-3">TOTAL</td>
                                <td class="text-center">{{ $total }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- CHART: RIGHT SIDE (20%) -->
            <div class="col-lg-4">
                <div class="card h-100 shadow-sm border-0">
                    <div class="card-header bg-primary text-white py-2">
                        <h6 class="mb-0 text-center">Frequency by Company</h6>
                    </div>
                    <div class="card-body p-2">
                        <canvas id="frequencyChart" class="w-100 h-100"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

{{-- ==================== CHART.JS + EXPORT SCRIPTS ==================== --}}
@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.5.23/jspdf.plugin.autotable.min.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function () {
    // === CHART.JS BAR CHART ===
    const ctx = document.getElementById('frequencyChart').getContext('2d');
    const labels = [
        @foreach($results as $row)
            "{{ addslashes($row->company_name) }}",
        @endforeach
    ];
    const data = [
        @foreach($results as $row)
            {{ $row->frequency }},
        @endforeach
    ];

    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: labels,
            datasets: [{
                label: 'Visits',
                data: data,
                backgroundColor: 'rgba(54, 162, 235, 0.7)',
                borderColor: 'rgba(54, 162, 235, 1)',
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false },
                tooltip: { mode: 'index', intersect: false }
            },
            scales: {
                y: { beginAtZero: true, ticks: { stepSize: 1 } },
                x: { ticks: { maxRotation: 45, minRotation: 45 } }
            }
        }
    });

    // === PDF EXPORT ===
    const { jsPDF } = window.jspdf;
    document.getElementById('pdfBtn').addEventListener('click', function () {
        const doc = new jsPDF('p', 'mm', 'a4');
        const title = `ATTENDANCE REPORT — {{ $monthName }} {{ $year }}`;
        doc.setFontSize(16);
        doc.text(title, 105, 20, { align: 'center' });

        const rows = [
            @foreach($results as $row)
                ['{{ $loop->iteration }}', '{{ addslashes($row->company_name) }}', '{{ $row->frequency }}'],
            @endforeach
            ['', 'TOTAL', '{{ $total }}']
        ];

        doc.autoTable({
            head: [['S/N', 'COMPANY', 'FREQUENCY']],
            body: rows,
            startY: 30,
            theme: 'grid',
            styles: { fontSize: 10, cellPadding: 3 },
            headStyles: { fillColor: [41, 128, 185], textColor: 255 },
            foot: [['', 'TOTAL', '{{ $total }}']],
            footStyles: { fillColor: [230, 230, 230], fontStyle: 'bold' }
        });

        doc.save('Lab-Visits-{{ $year }}-{{ $startDate->format('m') }}.pdf');
    });

    // === EXCEL & CSV EXPORT ===
    function exportTable(format) {
        let data = 'S/N,COMPANY,FREQUENCY\n';
        @foreach($results as $row)
            data += '{{ $loop->iteration }},"{{ addslashes($row->company_name) }}",{{ $row->frequency }}\n';
        @endforeach
        data += ',TOTAL,{{ $total }}\n';

        const blob = new Blob([data], { type: format === 'csv' ? 'text/csv' : 'application/vnd.ms-excel' });
        const url = URL.createObjectURL(blob);
        const a = document.createElement('a');
        a.href = url;
        a.download = `Lab-Visits-{{ $year }}-{{ $startDate->format('m') }}.${format}`;
        a.click();
        URL.revokeObjectURL(url);
    }

    document.getElementById('excelBtn').addEventListener('click', () => exportTable('xls'));
    document.getElementById('csvBtn').addEventListener('click', () => exportTable('csv'));
});
</script>

{{-- Layout & Print Styles --}}
<style>
html, body, #app, .content-wrapper, .content { height: 100%; margin: 0; }
.d-flex.h-100 { height: 100vh; }

.table-responsive.h-100 { display: flex; flex-direction: column; min-height: 0; }
#attendanceTable { display: table; width: 100% !important; table-layout: fixed; }
#attendanceTable th, #attendanceTable td { word-wrap: break-word; }

thead.sticky-top { position: sticky; top: 0; z-index: 10; }

#frequencyChart { height: 100% !important; width: 100% !important; }

@media print {
    .bg-light, .btn, form, .col-lg-4 { display: none !important; }
    .col-lg-8 { width: 100% !important; max-width: 100% !important; }
    .table { font-size: 12px; width: 100% !important; }
    .table thead { background-color: #cfe2ff !important; color: black !important; }
    .table th, .table td { border: 1px solid #000 !important; }
    .card, .card-body { padding: 0; margin: 0; }
}
</style>
@endpush