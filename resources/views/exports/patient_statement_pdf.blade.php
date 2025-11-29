<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title>Patient Statement Report</title>
    <style>
        body { font-family: Arial, sans-serif; color: #333; }
        .header { text-align: center; margin-bottom: 20px; }
        .header h1 { margin: 0; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background: #f2f2f2; }
    </style>
</head>
<body>
    <div class="header">
        <h1>{{ config('app.name') }}</h1>
        <h2>Patient Statement Report</h2>
        @if($startDate || $endDate)
            <p>Period: {{ $startDate ?? 'N/A' }} - {{ $endDate ?? 'N/A' }}</p>
        @endif
    </div>

    @if(!empty($patientData) && count($patientData) > 0)
        @php $first = $patientData[0]; @endphp
        @if(isset($first['Patient']))
            <h3>Patient List</h3>
            <table>
                <thead>
                    <tr>
                        @foreach(array_keys($first) as $h)
                            <th>{{ $h }}</th>
                        @endforeach
                    </tr>
                </thead>
                <tbody>
                    @foreach($patientData as $row)
                        <tr>
                            @foreach($row as $cell)
                                <td>{{ $cell }}</td>
                            @endforeach
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <h3>Patient Statement Details</h3>
            <table>
                <thead>
                    <tr>
                        @foreach(array_keys($first) as $h)
                            <th>{{ $h }}</th>
                        @endforeach
                    </tr>
                </thead>
                <tbody>
                    @foreach($patientData as $row)
                        <tr>
                            @foreach($row as $cell)
                                <td>{{ $cell }}</td>
                            @endforeach
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    @else
        <p>No records found for the selected period.</p>
    @endif
</body>
</html>
