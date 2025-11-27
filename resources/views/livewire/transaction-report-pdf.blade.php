<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        body {
            font-family: Arial, sans-serif;
            color: #333;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        table th {
            background-color: #007bff;
            color: white;
            padding: 10px;
            text-align: left;
            border: 1px solid #007bff;
        }
        table td {
            padding: 8px;
            border: 1px solid #ddd;
        }
    </style>
</head>
<body>
    <h1>Transaction Report</h1>
    <p>Generated on {{ \Carbon\Carbon::now()->format('M d, Y H:i:s') }}</p>
    <p>Report Period: {{ $startDate->format('M d, Y') }} to {{ $endDate->format('M d, Y') }}</p>
    
    <table>
        <thead>
            <tr>
                <th>Transaction ID</th>
                <th>Date</th>
                <th>Patient</th>
                <th>Type</th>
                <th>Payment Method</th>
                <th>Status</th>
                <th>Amount</th>
            </tr>
        </thead>
        <tbody>
            @if(count($transactions) > 0)
                @foreach($transactions as $transaction)
                    <tr>
                        <td>{{ $transaction['transaction_id'] ?? 'N/A' }}</td>
                        <td>{{ isset($transaction['date']) ? $transaction['date']->format('M d, Y') : 'N/A' }}</td>
                        <td>{{ isset($transaction['user']['name']) ? $transaction['user']['name'] : 'N/A' }}</td>
                        <td>{{ $transaction['type'] ?? 'N/A' }}</td>
                        <td>{{ $transaction['payment_type'] ?? 'N/A' }}</td>
                        <td>{{ $transaction['status'] ?? 'N/A' }}</td>
                        <td>${{ number_format($transaction['amount'] ?? 0, 2) }}</td>
                    </tr>
                @endforeach
                <tr style="background-color: #f0f0f0; font-weight: bold;">
                    <td colspan="6" style="text-align: right;">Total Amount:</td>
                    <td>${{ number_format($totalAmount, 2) }}</td>
                </tr>
            @else
                <tr>
                    <td colspan="7" style="text-align: center;">No transactions found</td>
                </tr>
            @endif
        </tbody>
    </table>
    
    <p style="margin-top: 30px; font-size: 12px; color: #999;">
        This report contains transaction data from your hospital management system.
    </p>
</body>
</html>
