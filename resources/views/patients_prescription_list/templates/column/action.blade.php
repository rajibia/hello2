<a href="{{url('patient/my-prescriptions') .'/'. $row->id}}" title="<?php echo __('messages.common.view') ?>"
   class="btn px-1 text-info fs-3 ps-0">
    <i class="fas fa-eye"></i>
</a>
@php
    $medicineBill = App\Models\MedicineBill::whereModelType('App\Models\Prescription')->whereModelId($row->id)->first();
@endphp
@if (Auth::user()->hasRole('Accountant') && isset($medicineBill->issue_status) && $medicineBill->issue_status == false)
    <a href="{{url('prescriptions'.'/'.$row->id.'/edit')}}" title="<?php echo __('messages.common.edit') ?>"
    class="btn px-1 text-primary fs-3 ps-0  edit-prescription-btn">
        <i class="fa-solid fa-pen-to-square"></i>
    </a>
@endif

@if (Auth::user()->hasRole('Pharmacist') && isset($medicineBill->payment_status) && $medicineBill->payment_status == true)
    <a href="{{url('prescriptions'.'/'.$row->id.'/edit')}}" title="<?php echo __('messages.common.edit') ?>"
    class="btn px-1 text-primary fs-3 ps-0  edit-prescription-btn">
        <i class="fa-solid fa-pen-to-square"></i>
    </a>
@endif
