@modulePermission('diagnosis-categories', 'view')
<a href="{{ route('diagnosis.category.upload') }}"
   class="btn btn-info me-2">{{ __('messages.diagnosis_category.upload_in_bulk') }}</a>
@endmodulePermission  
@modulePermission('diagnosis-categories', 'add')
<a href="javascript:void(0)" data-bs-toggle="modal" data-bs-target="#add_diagnosis_categories_modal"
   class="btn btn-primary">{{ __('messages.diagnosis_category.new_icd') }}</a>
@endmodulePermission