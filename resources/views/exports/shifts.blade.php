<table border="1" cellpadding="5" cellspacing="0" style="width: 100%; border-collapse: collapse; font-family: Arial, sans-serif;">
    <thead>
        <tr style="background-color: #f2f2f2; text-align: left;">
            <th>{{ __('messages.common.no') }}</th>
            <th>{{ __('messages.shift.shift_name') }}</th>
            <th>{{ __('messages.shift.shift_start') }}</th>
            <th>{{ __('messages.shift.shift_end') }}</th>
            <th>{{ __('messages.shift.break_duration') }}</th>
        </tr>
    </thead>
    <tbody>
        @foreach($shifts as $shift)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $shift->shift_name }}</td>
                <td>{{ $shift->shift_start }}</td>
                <td>{{ $shift->shift_end }}</td>
                <td>{{ $shift->break_duration }} mins</td>
            </tr>
        @endforeach
    </tbody>
</table>
