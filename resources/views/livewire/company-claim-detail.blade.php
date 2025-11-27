<DOCUMENT filename="company-claim-detail.blade.php">
<div>
    <style>
        .nav-tabs .nav-item .nav-link:after { border-bottom: 0 !important; }
        table th { padding: 0.5rem !important; }
        .live-search-input {
            font-size: 1.2rem;
            padding: 15px;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        }
        .patient-section { transition: all 0.3s ease; position: relative; }
        .patient-section.hidden { display: none !important; }
        .patient-section.highlight {
            background-color: #fff8e1 !important;
            border-left: 5px solid #ffc107;
            padding-left: 10px;
        }
        .no-results {
            text-align: center;
            padding: 60px 20px;
            color: #6c757d;
            font-size: 1.4rem;
        }
        .patient-export-buttons {
            position: absolute;
            top: 8px;
            right: 10px;
            z-index: 10;
            gap: 4px;
            opacity: 0;
            transition: opacity 0.3s ease;
        }
        .patient-section:hover .patient-export-buttons {
            opacity: 1;
        }
        .patient-export-btn {
            font-size: 0.75rem;
            padding: 0.25rem 0.5rem;
            border-radius: 6px;
        }
        .export-btn {
            font-size: 0.875rem;
            padding: 0.35rem 0.75rem;
        }
        @media print {
            .no-print, #liveSearchBox, .export-buttons, .patient-export-buttons { display: none !important; }
            .print-only { display: block !important; }
            a { text-decoration: none !important; color: inherit !important; }
            .badge { background-color: transparent !important; color: #000 !important; border: 1px solid #ddd; padding: 4px 8px; }
            .print-table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
            .print-table th, .print-table td { border: 1px solid #ddd; padding: 8px; text-align: left; }
            .print-header { text-align: center; margin-bottom: 30px; }
            body * { visibility: hidden; }
            #companyClaimPrintSection, #companyClaimPrintSection * { visibility: visible; }
            #companyClaimPrintSection { position: absolute; left: 0; top: 0; width: 100%; background: white; padding: 20px; }
            .card, .card-body, .container-fluid { border: none !important; box-shadow: none !important; padding: 0 !important; }
        }
        .print-only { display: none; }
    </style>

    <!-- HEADER + GLOBAL EXPORT + SEARCH -->
    <div class="mb-5 no-print">
        <div class="d-flex flex-column flex-lg-row justify-content-between align-items-start align-items-lg-center gap-4 mb-4">
            <h3 class="card-title mb-0">
                <span class="text-gray-800">{{ __('Company Claim Report') }}</span><br>
                <span class="mt-1 fw-bold fs-6 text-muted">: {{ $company->name }}</span>
            </h3>
            <div class="d-flex flex-wrap gap-2 export-buttons">
                <button id="exportAllPdf" class="btn btn-danger export-btn">
                    <i class="fas fa-file-pdf"></i> All PDF
                </button>
                <button id="exportAllExcel" class="btn btn-success export-btn">
                    <i class="fas fa-file-excel"></i> All Excel
                </button>
                <button id="exportAllCsv" class="btn btn-info export-btn text-white">
                    <i class="fas fa-file-csv"></i> All CSV
                </button>
                <button onclick="window.print()" class="btn btn-primary export-btn">
                    <i class="fas fa-print"></i> Print
                </button>
                <a href="{{ route('reports.company-claim') }}" class="btn btn-outline-secondary export-btn">
                    <i class="fas fa-arrow-left"></i> Back
                </a>
            </div>
        </div>

        <div class="col-12 col-lg-8 mx-auto">
            <input type="text" id="liveSearchBox" class="form-control live-search-input"
                   placeholder="Type patient name or ID to search instantly..." autocomplete="off">
            <small class="text-success d-block mt-2 text-center">
                Real-time search • Results update as you type
            </small>
        </div>
    </div>

    <!-- SCREEN VERSION -->
    <div class="card mb-5 no-print">
        <div class="card-header border-0 pt-5 pb-3">
            <h3 class="mb-4">{{ __('Patient Claims') }}</h3>
        </div>
        <div class="card-body pt-0 fs-6 py-8 px-8 px-lg-10 text-gray-700">
            <div class="table-responsive" id="patientsContainer">
                @forelse($patientBills['patients'] as $patient)
                    <div class="patient-section mb-7 position-relative"
                         data-search="{{ strtolower($patient->user->first_name . ' ' . ($patient->user->last_name ?? '') . ' ' . $patient->patient_unique_id) }}">

                        <!-- Per-Patient Export Buttons -->
                        <div class="d-flex patient-export-buttons shadow-sm">
                            <button class="btn btn-sm btn-outline-danger patient-export-btn export-patient-pdf" data-patient="{{ $patient->patient_unique_id }}">
                                <i class="fas fa-file-pdf"></i>
                            </button>
                            <button class="btn btn-sm btn-outline-success patient-export-btn export-patient-excel" data-patient="{{ $patient->patient_unique_id }}">
                                <i class="fas fa-file-excel"></i>
                            </button>
                            <button class="btn btn-sm btn-outline-info patient-export-btn export-patient-csv" data-patient="{{ $patient->patient_unique_id }}">
                                <i class="fas fa-file-csv"></i>
                            </button>
                        </div>

                        <h4 class="mb-4 text-primary">
                            {{ $patient->user->first_name }} {{ $patient->user->last_name ?? '' }}
                            <span class="text-muted fs-6">({{ $patient->patient_unique_id }})</span>
                        </h4>

                        @if($patient->invoices->count() || $patient->medicine_bills->count() || $patient->ipd_bills->count() ||
                            $patient->pathologyTests->count() || $patient->radiologyTests->count() || $patient->maternity->count())

                            <table class="table table-striped table-bordered table-export" data-patient-id="{{ $patient->patient_unique_id }}">
                                <thead class="table-light">
                                    <tr>
                                        <th>Bill Type</th>
                                        <th>Item/Individual</th>
                                        <th>Quantity</th>
                                        <th>Bill/Invoice #</th>
                                        <th>Date</th>
                                        <th>Amount</th>
                                        <th>Paid</th>
                                        <th>Balance</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <!-- OPD Invoices -->
                                    @foreach($patient->invoices as $invoice)
                                        <tr>
                                            <td>OPD Invoice</td>
                                            <td>{{ $invoice->individual ?? '-' }}</td>
                                            <td>{{ $invoice->quantity ?? '1' }}</td>
                                            <td>{{ $invoice->invoice_id }}</td>
                                            <td>{{ $invoice->invoice_date?->format('d M Y') }}</td>
                                            <td>{{ number_format($invoice->amount, 2) }}</td>
                                            <td>{{ number_format($invoice->amount - $invoice->balance, 2) }}</td>
                                            <td>{{ number_format($invoice->balance, 2) }}</td>
                                            <td>{{ $invoice->balance <= 0 ? 'Paid' : 'Unpaid' }}</td>
                                        </tr>
                                    @endforeach

                                    <!-- Medicine Bills -->
                                    @php
                                        $medicineRows = collect();
                                        foreach($patient->medicine_bills as $bill) {
                                            $items = DB::table('sale_medicines')
                                                ->join('medicines', 'sale_medicines.medicine_id', '=', 'medicines.id')
                                                ->where('sale_medicines.medicine_bill_id', $bill->id)
                                                ->select('medicines.name', 'sale_medicines.sale_quantity as qty')
                                                ->get();
                                            if ($items->isEmpty()) {
                                                $medicineRows->push([
                                                    'bill' => $bill,
                                                    'name' => 'Medicines Issued',
                                                    'qty'  => '-'
                                                ]);
                                            } else {
                                                foreach ($items as $item) {
                                                    $medicineRows->push([
                                                        'bill' => $bill,
                                                        'name' => $item->name ?? 'Unknown Medicine',
                                                        'qty'  => $item->qty ?? 1
                                                    ]);
                                                }
                                            }
                                        }
                                    @endphp
                                    @foreach($medicineRows as $row)
                                        <tr>
                                            <td>Medicine Bill</td>
                                            <td>{{ $row['name'] }}</td>
                                            <td>{{ $row['qty'] }}</td>
                                            <td>{{ $row['bill']->bill_number }}</td>
                                            <td>{{ \Carbon\Carbon::parse($row['bill']->bill_date)->format('d M Y') }}</td>
                                            <td>{{ number_format($row['bill']->net_amount, 2) }}</td>
                                            <td>{{ number_format($row['bill']->paid_amount ?? 0, 2) }}</td>
                                            <td>{{ number_format($row['bill']->net_amount - ($row['bill']->paid_amount ?? 0), 2) }}</td>
                                            <td>{{ $row['bill']->payment_status == 1 ? 'Paid' : 'Unpaid' }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>

                            @php
                                $totalAmount = $patient->invoices->sum('amount') + $patient->medicine_bills->sum('net_amount');
                                $totalPaid = $patient->invoices->sum(fn($i) => $i->amount - $i->balance) + $patient->medicine_bills->sum('paid_amount');
                                $totalBalance = $totalAmount - $totalPaid;
                            @endphp
                            <div class="text-end fw-bold fs-5 text-danger mt-3">
                                Patient Total Due: {{ getCurrencySymbol() }}{{ number_format($totalBalance, 2) }}
                            </div>
                        @else
                            <div class="alert alert-info">No data found.</div>
                        @endif
                        <hr class="my-5">
                    </div>
                @empty
                    <div class="text-center py-10 text-muted">
                        <h4>No patient claims found.</h4>
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    <!-- SCRIPTS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.5.28/jspdf.plugin.autotable.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/FileSaver.js/2.0.5/FileSaver.min.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const { jsPDF } = window.jspdf;

            // Live Search
            const searchBox = document.getElementById('liveSearchBox');
            const container = document.getElementById('patientsContainer');
            const sections = container.querySelectorAll('.patient-section');

            searchBox.addEventListener('keyup', function () {
                const query = this.value.trim().toLowerCase();
                let visibleCount = 0;

                sections.forEach(section => {
                    const text = section.getAttribute('data-search') || '';
                    if (text.includes(query)) {
                        section.classList.remove('hidden');
                        if (query) section.classList.add('highlight');
                        visibleCount++;
                    } else {
                        section.classList.add('hidden');
                        section.classList.remove('highlight');
                    }
                });

                let noResults = container.querySelector('.no-results');
                if (!noResults && visibleCount === 0 && query !== '') {
                    container.innerHTML += `<div class="no-results"><h4>No patient found matching "<strong>${query}</strong>"</h4><p class="text-muted">Try searching by name or patient ID</p></div>`;
                } else if (noResults && (visibleCount > 0 || query === '')) {
                    noResults.remove();
                }
            });

            // Helper: Get table rows for a specific patient
            function getPatientRows(patientId) {
                const table = document.querySelector(`table[data-patient-id="${patientId}"]`);
                if (!table) return [];
                return Array.from(table.querySelectorAll('tbody tr')).map(tr =>
                    Array.from(tr.cells).map(cell => cell.innerText.trim())
                );
            }

            // Helper: Get patient name
            function getPatientName(patientId) {
                const section = document.querySelector(`.patient-section [data-patient="${patientId}"]`)?.closest('.patient-section');
                return section ? section.querySelector('h4').innerText.trim() : 'Patient';
            }

            // Individual Patient Export (PDF/Excel/CSV)
            document.querySelectorAll('.export-patient-pdf').forEach(btn => {
                btn.addEventListener('click', function () {
                    const patientId = this.dataset.patient;
                    const patientName = getPatientName(patientId);
                    const data = getPatientRows(patientId);

                    const doc = new jsPDF('p', 'mm', 'a4');
                    doc.setFontSize(16);
                    doc.text(`Claim Report - ${patientName}`, 14, 15);
                    doc.setFontSize(10);
                    doc.text(`Company: {{ $company->name }} | Generated: {{ now()->format('d M Y h:i A') }}`, 14, 22);

                    doc.autoTable({
                        head: [["Bill Type", "Item", "Qty", "Bill #", "Date", "Amount", "Paid", "Balance", "Status"]],
                        body: data,
                        startY: 30,
                        theme: 'grid',
                        styles: { fontSize: 9 },
                        headStyles: { fillColor: [220, 53, 69] }
                    });

                    doc.save(`${patientName.replace(/[^a-zA-Z0-9]/g, '_')}_Claim_Report.pdf`);
                });
            });

            document.querySelectorAll('.export-patient-excel').forEach(btn => {
                btn.addEventListener('click', function () {
                    const patientId = this.dataset.patient;
                    const patientName = getPatientName(patientId);
                    const rows = getPatientRows(patientId);

                    const data = [["Patient", "Bill Type", "Item", "Qty", "Bill #", "Date", "Amount", "Paid", "Balance", "Status"]];
                    rows.forEach(row => data.push([patientName, ...row]));

                    const ws = XLSX.utils.aoa_to_sheet(data);
                    const wb = XLSX.utils.book_new();
                    XLSX.utils.book_append_sheet(wb, ws, "Claim");
                    XLSX.writeFile(wb, `${patientName.replace(/[^a-zA-Z0-9]/g, '_')}_Claim_Report.xlsx`);
                });
            });

            document.querySelectorAll('.export-patient-csv').forEach(btn => {
                btn.addEventListener('click', function () {
                    const patientId = this.dataset.patient;
                    const patientName = getPatientName(patientId);
                    const rows = getPatientRows(patientId);

                    let csv = "Patient,Bill Type,Item,Qty,Bill #,Date,Amount,Paid,Balance,Status\n";
                    rows.forEach(row => {
                        csv += `"${patientName}","${row.join('","')}""\n`;
                    });

                    const blob = new Blob([csv], { type: 'text/csv' });
                    const url = URL.createObjectURL(blob);
                    const a = document.createElement('a');
                    a.href = url;
                    a.download = `${patientName.replace(/[^a-zA-Z0-9]/g, '_')}_Claim_Report.csv`;
                    a.click();
                });
            });

            // Global Export (All Visible Patients) - Reuse your previous logic
            // (You can keep the old global export buttons working too)
        });
    </script>
</div>
</DOCUMENT>