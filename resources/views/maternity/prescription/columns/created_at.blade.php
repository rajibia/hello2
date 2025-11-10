<div class="d-flex align-items-center">
    <div class="badge bg-light-info">
        <div>
            {{ \Carbon\Carbon::parse($row->created_at)->translatedFormat('jS M, Y g:i A') }}
        </div>
    </div>
</div>
