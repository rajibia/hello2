<div class="d-flex align-items-center">
    <div class="d-flex flex-column">
        <a href="{{route('suppliers.show', $row->id)}}" class="mb-1 text-decoration-none fs-6">
            {{$row->name}}
        </a>
        <span class="fs-6">{{$row->email}}</span>
    </div>
</div>
