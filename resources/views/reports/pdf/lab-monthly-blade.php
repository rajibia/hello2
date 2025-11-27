<!DOCTYPE html>
<html>
<head>
    <style>
        body { font-family: DejaVu Sans; font-size: 12px; }
        table { width: 100%; border-collapse: collapse; margin: 20px 0; }
        th, td { border: 1px solid #000; padding: 8px; text-align: center; }
        th { background-color: #f0f0f0; }
        .header { text-align: center; margin-bottom: 30px; }
        .signature { margin-top: 50px; }
    </style>
</head>
<body>
    <div class="header">
        <h3>LABORATORY MONTHLY REPORT</h3>
        <p><strong>MONTH:</strong> {{ $monthName }} <strong>YEAR:</strong> {{ $year }}</p>
    </div>

    <h4>ATTENDANCE</h4>
    <table>
        <tr><th>S/N</th><th>COMPANY</th><th>FREQUENCY</th></tr>
        @foreach($attendance as $i => $a)
        <tr><td>{{ $i+1 }}</td><td>{{ $a['company'] }}</td><td>{{ $a['frequency'] }}</td></tr>
        @endforeach
        <tr><td colspan="2"><strong>TOTAL</strong></td><td><strong>{{ $totalAttendance }}</strong></td></tr>
    </table>

    <h4>TEST INVESTIGATIONS DONE</h4>
    <table>
        <tr><th>S/N</th><th>ANALYTE</th><th>NEGATIVE</th><th>POSITIVE</th><th>TOTAL</th></tr>
        @foreach($investigations as $i => $t)
        <tr>
            <td>{{ $i+1 }}</td>
            <td>{{ $t['test_name'] }}</td>
            <td>{{ $t['negative'] ?: '-' }}</td>
            <td>{{ $t['positive'] ?: '-' }}</td>
            <td>{{ $t['total'] }}</td>
        </tr>
        @endforeach
    </table>

    <div class="signature">
        <p>COMPILED BY: {{ $compiledBy }}</p>
        <p>POSITION: Medical Laboratory Technician</p>
        <p>DATE: {{ now()->format('jS F Y') }}</p>
    </div>
</body>
</html>