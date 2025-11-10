<table>
    <thead>
    <tr>
        <th>{{ __('messages.common.no') }}</th>
        <th>{{ __('messages.package.diagnosis') }} Name</th>
        <th>G - DRG Code</th>
        <th>ICD 10 Code</th>
        <th>Insurance</th>
        <th>{{ __('messages.diagnosis.rate') }}</th>
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
    @foreach($diagnosis as $diagnosis)
        <tr>
            <td>{{ $loop->iteration }}</td>
            <td>{{ $diagnosis->name }}</td>
            <td>{{ $diagnosis->gdrg_code }}</td>
            <td>{{ $diagnosis->icd_10_code }}</td>
            <td>{{ $diagnosis->insurance_name }}</td>
            <td>{{ number_format($diagnosis->tariff,2) }}</td>
            <td>{{ number_format($diagnosis->topup,2) }}</td>
            <td>{{ number_format($diagnosis->non_insured_amount,2) }}</td>
            <td>{{ $diagnosis->age }}</td>
            <td>{{ $diagnosis->grouping }}</td>
            <td>{{ $diagnosis->speciality_code }}</td>
            <td>{{ $diagnosis->speciality_description }}</td>
            <td>{{ ($diagnosis->status == 1) ? __('messages.common.active') : __('messages.common.de_active') }}</td>
        </tr>
    @endforeach
    </tbody>
</table>
