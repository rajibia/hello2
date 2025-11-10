<table>
    <thead>
    <tr>
        <th>{{ __('messages.common.no') }}</th>
        <th>{{ __('messages.package.scan') }}</th>
        <th>G - DRG Code</th>
        <th>Insurance</th>
        <th>{{ __('messages.scan.rate') }}</th>
        <th>Top Up</th>
        <th>Non Insured Amount</th>
        <th>Flag</th>
        <th>{{ __('messages.common.status') }}</th>
    </tr>
    </thead>
    
    <tbody>
    @foreach($scans as $scan)
        <tr>
            <td>{{ $loop->iteration }}</td>
            <td>{{ $scan->name }}</td>
            <td>{{ $scan->gdrg_code }}</td>
            <td>{{ $scan->insurance_name }}</td>
            <td>{{ number_format($scan->tariff,2) }}</td>
            <td>{{ number_format($scan->topup,2) }}</td>
            <td>{{ number_format($scan->non_insured_amount,2) }}</td>
            <td>{{ $scan->flag }}</td>
            <td>{{ ($scan->status == 1) ? __('messages.common.active') : __('messages.common.de_active') }}</td>
        </tr>
    @endforeach
    </tbody>
</table>
