<div class="d-flex align-items-center mt-2">
    <!--if (empty($row->user->gender != '1'))-->
    @if ($row->patientUser->gender != '1')
        <span class="badge bg-light-info">{{ __('messages.user.male') }}</span>
    @else
        <span class="badge bg-light-success">{{ __('messages.user.female') }}</span>
    @endif
</div>
