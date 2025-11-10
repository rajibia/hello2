<table>
    <thead>
    <tr>
        <th>{{ __('messages.common.no') }}</th>
        <th>{{ __('messages.insurance.insurance') }}</th>
        {{-- <th>{{ __('messages.insurance.service_tax') }}</th>
        <th>{{ __('messages.insurance.discount') }}</th>
        <th>{{ __('messages.insurance.insurance_no') }}</th> --}}
        <th>{{ __('messages.insurance.insurance_code') }}</th>
        {{-- <th>{{ __('messages.insurance.hospital_rate') }}</th>
        <th>{{ __('messages.common.total') }}</th> --}}
        <th>Other Identification</th>
        <th>Card Type</th>
        <th>Claim check code</th>
        <th>Non-insurance medication</th>
        <th>Claim code count</th>
        <th>Membership number count</th>
        <th>Card serial number count</th>
        <th>Visit per month</th>
        <th>{{ __('messages.common.status') }}</th>
        {{-- <th>{{ __('messages.insurance.remark') }}</th> --}}        
    </tr>
    </thead>
    <tbody>
    @foreach($insurances as $insurance)
        <tr>
            <td>{{ $loop->iteration }}</td>
            <td>{{ $insurance->name }}</td>
            {{-- <td>{{ number_format($insurance->service_tax, 2) }}</td>
            <td>{{ isset($insurance->discount) ? $insurance->discount.'%' : __('messages.common.n/a') }}</td>
            <td>{{ $insurance->insurance_no }}</td> --}}
            <td>{{ $insurance->insurance_code }}</td>
            {{-- <td>{{ number_format($insurance->hospital_rate, 2) }}</td>
            <td>{{ number_format($insurance->total, 2) }}</td> --}}
            <td>{{ $insurance->other_identification }}</td>
            <td>{{ $insurance->card_type }}</td>
            <td>{{ $insurance->claim_check_code }}</td>
            <td>{{ $insurance->non_insurance_medication }}</td>
            <td>{{ $insurance->claim_code_count }}</td>
            <td>{{ $insurance->membership_no_count }}</td>
            <td>{{ $insurance->card_serial_no_count }}</td>
            <td>{{ $insurance->visit_per_month }}</td>
            <td>{{ ($insurance->status === 1) ? __('messages.common.active') : __('messages.common.de_active') }}</td>
            {{-- <td>{!! !empty($insurance->remark) ? nl2br(e($insurance->remark)) : __('messages.common.n/a') !!}</td> --}}
        </tr>
        <tr></tr>
        <tr>
            <td>
                <table>
                    <thead>
                    <tr>
                        <th>{{ __('messages.common.no') }}</th>
                        <th>Package name</th>
                        {{-- <th>{{ __('messages.insurance.diseases_name') }}</th>
                        <th>{{ __('messages.insurance.diseases_charge') }}</th> --}}
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($insurance->insurancePackages as $insurancePackage)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $insurancePackage->package_name }}</td>
                            {{-- <td>{{ $insuranceDisease->disease_name }}</td>
                            <td>{{ number_format($insuranceDisease->disease_charge, 2) }}</td> --}}
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </td>
        </tr>
    @endforeach
    </tbody>
</table>
