<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Patient Claim – {{ $patient->user->full_name }}</title>
    <style>
        body { 
            font-family: DejaVu Sans, Arial, sans-serif; 
            font-size: 10pt; 
            margin: 15mm; 
            color: #000;
        }
        .header { 
            text-align: center; 
            margin-bottom: 15mm; 
        }
        h1, h2 { 
            margin: 0; 
            font-weight: bold; 
        }
        h1 { font-size: 16pt; }
        h2 { font-size: 13pt; margin-top: 5mm; }
        .info { 
            font-size: 10pt; 
            margin: 3mm 0; 
        }
        table { 
            width: 100%; 
            border-collapse: collapse; 
            margin-top: 10mm; 
            font-size: 9pt;
        }
        th, td { 
            border: 1px solid #999; 
            padding: 4px 6px; 
            text-align: left; 
        }
        th { 
            background: #f0f0f0; 
            font-weight: bold; 
        }
        .badge { 
            padding: 2px 6px; 
            border-radius: 3px; 
            color: #fff; 
            font-size: 8pt; 
            display: inline-block;
        }
        .bg-info    { background: #17a2b8; }
        .bg-success { background: #28a745; }
        .bg-danger  { background: #dc3545; }
        .total-row { 
            font-weight: bold; 
            background: #f8f9fa; 
        }
        .text-right { text-align: right; }
    </style>
</head>
<body>

<div class="header">
    <h1>{{ config('app.name', 'Hospital System') }}</h1>
    <h2>Patient Claim Report</h2>
    <div class="info">
        <strong>Patient:</strong> {{ $patient->user->full_name }} ({{ $patient->patient_unique_id }})<br>
        <strong>Company:</strong> {{ $company->name }}<br>
        <strong>Period:</strong> 
            {{ \Carbon\Carbon::parse($fromDate)->format('d M Y') }} – 
            {{ \Carbon\Carbon::parse($toDate)->format('d M Y') }}
    </div>
</div>

<table>
    <thead>
        <tr>
            <th>Bill Type</th>
            <th>Individual</th>
            <th>Qty</th>
            <th>Bill/Invoice #</th>
            <th>Date</th>
            <th class="text-right">Amount</th>
            <th class="text-right">Paid</th>
            <th class="text-right">Balance</th>
            <th>Status</th>
        </tr>
    </thead>
    <tbody>

        <!-- Medicine Bills -->
        @foreach($medicineItems as $item)
            @php
                $amount = $item->amount;
                $paid = $item->paid_amount ?? 0;
                $balance = $amount - $paid;
            @endphp
            <tr>
                <td><span class="badge bg-info">Medicine Bill</span></td>
                <td>{{ $item->medicine_name }}</td>
                <td>{{ $item->sale_quantity }}</td>
                <td>{{ $item->bill_number }}</td>
                <td>{{ \Carbon\Carbon::parse($item->bill_date)->format('Y-m-d') }}</td>
                <td class="text-right">{{ number_format($amount, 2) }}</td>
                <td class="text-right">{{ number_format($paid, 2) }}</td>
                <td class="text-right">{{ number_format($balance, 2) }}</td>
                <td><span class="badge {{ $balance <= 0 ? 'bg-success' : 'bg-danger' }}">{{ $balance <= 0 ? 'Paid' : 'Unpaid' }}</span></td>
            </tr>
        @endforeach

        <!-- OPD Invoices -->
        @foreach($patient->invoices as $i)
            <tr>
                <td><span class="badge bg-info">OPD Invoice</span></td>
                <td>{{ $patient->user->full_name }}</td>
                <td></td>
                <td>{{ $i->invoice_id }}</td>
                <td>{{ $i->invoice_date->format('Y-m-d') }}</td>
                <td class="text-right">{{ number_format($i->amount, 2) }}</td>
                <td class="text-right">{{ number_format($i->amount - $i->balance, 2) }}</td>
                <td class="text-right">{{ number_format($i->balance, 2) }}</td>
                <td><span class="badge {{ $i->balance <= 0 ? 'bg-success' : 'bg-danger' }}">{{ $i->balance <= 0 ? 'Paid' : 'Unpaid' }}</span></td>
            </tr>
        @endforeach

        <!-- IPD Bills -->
        @foreach($patient->ipd_bills as $ipd)
            @if($ipd->bill)
                @php
                    $amount = $ipd->bill->net_payable_amount;
                    $paid = $ipd->bill->total_payments;
                    $balance = $amount - $paid;
                @endphp
                <tr>
                    <td><span class="badge bg-info">IPD Bill</span></td>
                    <td>{{ $patient->user->full_name }}</td>
                    <td></td>
                    <td>{{ $ipd->bill->bill_id }}</td>
                    <td>{{ $ipd->created_at?->format('Y-m-d') }}</td>
                    <td class="text-right">{{ number_format($amount, 2) }}</td>
                    <td class="text-right">{{ number_format($paid, 2) }}</td>
                    <td class="text-right">{{ number_format($balance, 2) }}</td>
                    <td><span class="badge {{ $balance <= 0 ? 'bg-success' : 'bg-danger' }}">{{ $balance <= 0 ? 'Paid' : 'Unpaid' }}</span></td>
                </tr>
            @endif
        @endforeach

        <!-- Pathology Tests -->
        @foreach($patient->pathologyTests as $t)
            @php $balance = $t->balance; @endphp
            <tr>
                <td><span class="badge bg-info">Pathology Test</span></td>
                <td>{{ $patient->user->full_name }}</td>
                <td></td>
                <td>{{ $t->test_id }}</td>
                <td>{{ $t->created_at?->format('Y-m-d') }}</td>
                <td class="text-right">{{ number_format($t->total, 2) }}</td>
                <td class="text-right">{{ number_format($t->total - $balance, 2) }}</td>
                <td class="text-right">{{ number_format($balance, 2) }}</td>
                <td><span class="badge {{ $balance <= 0 ? 'bg-success' : 'bg-danger' }}">{{ $balance <= 0 ? 'Paid' : 'Unpaid' }}</span></td>
            </tr>
        @endforeach

        <!-- Radiology Tests -->
        @foreach($patient->radiologyTests as $t)
            @php $balance = $t->balance; @endphp
            <tr>
                <td><span class="badge bg-info">Radiology Test</span></td>
                <td>{{ $patient->user->full_name }}</td>
                <td></td>
                <td>{{ $t->test_id }}</td>
                <td>{{ $t->created_at?->format('Y-m-d') }}</td>
                <td class="text-right">{{ number_format($t->total, 2) }}</td>
                <td class="text-right">{{ number_format($t->total - $balance, 2) }}</td>
                <td class="text-right">{{ number_format($balance, 2) }}</td>
                <td><span class="badge {{ $balance <= 0 ? 'bg-success' : 'bg-danger' }}">{{ $balance <= 0 ? 'Paid' : 'Unpaid' }}</span></td>
            </tr>
        @endforeach

        <!-- Maternity -->
        @foreach($patient->maternity as $m)
            @php $balance = $m->balance; @endphp
            <tr>
                <td><span class="badge bg-info">Maternity</span></td>
                <td>{{ $patient->user->full_name }}</td>
                <td></td>
                <td>{{ $m->case_id }}</td>
                <td>{{ $m->created_at?->format('Y-m-d') }}</td>
                <td class="text-right">{{ number_format($m->standard_charge, 2) }}</td>
                <td class="text-right">{{ number_format($m->standard_charge - $balance, 2) }}</td>
                <td class="text-right">{{ number_format($balance, 2) }}</td>
                <td><span class="badge {{ $balance <= 0 ? 'bg-success' : 'bg-danger' }}">{{ $balance <= 0 ? 'Paid' : 'Unpaid' }}</span></td>
            </tr>
        @endforeach
    </tbody>
    <tfoot>
        <tr class="total-row">
            <td colspan="5" class="text-right"><strong>Patient Total</strong></td>
            <td class="text-right"><strong>{{ number_format(
                $patient->invoices->sum('amount') +
                $patient->medicine_bills->sum('net_amount') +
                $patient->ipd_bills->sum(fn($b) => $b->bill?->net_payable_amount ?? 0) +
                $patient->pathologyTests->sum('total') +
                $patient->radiologyTests->sum('total') +
                $patient->maternity->sum('standard_charge'), 2) }}</strong></td>
            <td class="text-right"><strong>{{ number_format(
                $patient->invoices->sum(fn($i) => $i->amount - $i->balance) +
                $patient->medicine_bills->sum(fn($b) => $b->net_amount - $b->balance) +
                $patient->ipd_bills->sum(fn($b) => $b->bill?->total_payments ?? 0) +
                $patient->pathologyTests->sum(fn($t) => $t->total - $t->balance) +
                $patient->radiologyTests->sum(fn($t) => $t->total - $t->balance) +
                $patient->maternity->sum(fn($m) => $m->standard_charge - $m->balance), 2) }}</strong></td>
            <td class="text-right"><strong>{{ number_format(
                $patient->invoices->sum('balance') +
                $patient->medicine_bills->sum('balance') +
                $patient->ipd_bills->sum(fn($b) => ($b->bill?->net_payable_amount ?? 0) - ($b->bill?->total_payments ?? 0)) +
                $patient->pathologyTests->sum('balance') +
                $patient->radiologyTests->sum('balance') +
                $patient->maternity->sum('balance'), 2) }}</strong></td>
            <td></td>
        </tr>
    </tfoot>
</table>

</body>
</html>