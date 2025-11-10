<table>
    <thead>
    <tr>
        <th>{{ __('messages.common.no') }}</th>
        <th>{{ __('messages.package.lab') }} Name</th>
        <th>G - DRG Code</th>
        <th>Insurance</th>
        <th>{{ __('messages.lab.rate') }}</th>
        <th>Top Up</th>
        <th>Non Insured Amount</th>
        <th>{{ __('messages.common.status') }}</th>
    </tr>
    </thead>
    
    <tbody>
    @foreach($labs as $lab)
        <tr>
            <td>{{ $loop->iteration }}</td>
            <td>{{ $lab->name }}</td>
            <td>{{ $lab->gdrg_code }}</td>
            <td>{{ $lab->insurance_name }}</td>
            <td>{{ number_format($lab->tariff,2) }}</td>
            <td>{{ number_format($lab->topup,2) }}</td>
            <td>{{ number_format($lab->non_insured_amount,2) }}</td>
            <td>{{ ($lab->status == 1) ? __('messages.common.active') : __('messages.common.de_active') }}</td>
        </tr>
    @endforeach
    </tbody>
</table>
