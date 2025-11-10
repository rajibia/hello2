<div class="d-flex align-items-center">
    {{ \Carbon\Carbon::parse($row->created_at)->format('d M Y') }}
</div>