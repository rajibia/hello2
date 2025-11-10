{{-- <a href="javascript:void(0)" data-id="{{$row->id}}" class="showMedicineBtn text-decoration-none" > {{$row->name}} </a> --}}
<div class="d-flex align-items-center">
    <div class="d-flex flex-column">
        <a href="{{route('medicines.show', $row->id)}}" class="mb-1 text-decoration-none fs-6">
            {{$row->name}}
        </a>
    </div>
</div>
