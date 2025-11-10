<div class="d-flex align-items-center mt-2">
    <!--if (empty($row->user->gender != '1'))-->
    @if (is_null($row->company_id))
        <span class="text-info">Individual</span>
    @else
        Company:
        <br>
        <span class="text-success"> {{$row->company->name ?? '--' }}</span>
    @endif
</div>
