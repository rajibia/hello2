<table>
    <thead>
    <tr>
        <th>{{ __('messages.common.no') }}</th>
        <th>{{ __('messages.package.procedure') }} Name</th>
        <th>G - DRG Code</th>
        <th>Insurance</th>
        <th>{{ __('messages.procedure.rate') }}</th>
        <th>Top Up</th>
        <th>Non Insured Amount</th>
        <th>Age</th>
        <th>Grouping</th>
        <th>Speciality Code</th>
        <th>Speciality Description</th>
        <th>{{ __('messages.common.status') }}</th>
    </tr>
    </thead>
    
    <tbody>
    @foreach($procedures as $procedure)
        <tr>
            <td>{{ $loop->iteration }}</td>
            <td>{{ $procedure->name }}</td>
            <td>{{ $procedure->gdrg_code }}</td>
            <td>{{ $procedure->insurance_name }}</td>
            <td>{{ number_format($procedure->tariff,2) }}</td>
            <td>{{ number_format($procedure->topup,2) }}</td>
            <td>{{ number_format($procedure->non_insured_amount,2) }}</td>
            <td>{{ $procedure->age }}</td>
            <td>{{ $procedure->grouping }}</td>
            <td>{{ $procedure->speciality_code }}</td>
            <td>{{ $procedure->speciality_description }}</td>
            <td>{{ ($procedure->status == 1) ? __('messages.common.active') : __('messages.common.de_active') }}</td>
        </tr>
    @endforeach
    </tbody>
</table>
