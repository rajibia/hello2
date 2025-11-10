<table>
    <thead>
    <tr>
        <th>{{ __('messages.common.no') }}</th>
        <th>{{ __('messages.package.service') }}</th>
        <th>G - DRG Code</th>
        <th>Insurance</th>
        <th>Age</th>
        <th>Charge Status</th>
        {{-- <th>{{ __('messages.service.quantity') }}</th> --}}
        <th>{{ __('messages.service.rate') }}</th>
        <th>Top Up</th>
        <th>Non Insured Amount</th>
        <th>Speciality Code</th>
        <th>{{ __('messages.common.status') }}</th>
        <th>{{ __('messages.common.description') }}</th>
    </tr>
    </thead>
    <tbody>
    @foreach($services as $service)
        <tr>
            <td>{{ $loop->iteration }}</td>
            <td>{{ $service->name }}</td>
            {{-- <td>{{ $service->quantity }}</td> --}}
            <td>{{ $service->icd_code }}</td>
            <td>{{ $service->insurance_name }}</td>
            <td>{{ $service->age }}</td>
            <td>{{ $service->charge_status }}</td>
            <td>{{ number_format($service->rate,2) }}</td>
            <td>{{ number_format($service->topup,2) }}</td>
            <td>{{ number_format($service->non_insured_amount,2) }}</td>
            <td>{{ $service->speciality_code }}</td>
            <td>{{ ($service->status == 1) ? __('messages.common.active') : __('messages.common.de_active') }}</td>
            <td>{!! !empty($service->description) ? nl2br(e($service->description)) : __('messages.common.n/a') !!}</td>
        </tr>
    @endforeach
    </tbody>
</table>
