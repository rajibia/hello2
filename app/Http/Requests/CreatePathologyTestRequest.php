<?php

namespace App\Http\Requests;

use App\Models\PathologyTest;
use Illuminate\Foundation\Http\FormRequest;

class CreatePathologyTestRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            // Required fields
            'patient_id' => 'required|exists:patients,id',
            'opd_id' => 'nullable|exists:opd_patient_departments,id',
            'ipd_id' => 'nullable|exists:ipd_patient_departments,id',
            'maternity_id' => 'nullable|exists:maternity_patients,id',
            'case_id' => 'required|exists:patient_cases,id',
            'template_id.*' => 'required|exists:pathology_test_templates,id',
            'report_date.*' => 'required|date',
            'doctor_id' => 'required|exists:doctors,id',

            // Optional fields
            'note' => 'nullable|string',
            'previous_report_value' => 'nullable|string|max:255',
            'lab_technician_id' => 'nullable|exists:lab_technicians,id',
            'collection_date' => 'nullable|date',
            'expected_date' => 'nullable|date',

            // Discount and Total
            'discount_percent' => 'nullable|numeric|min:0|max:100',
        ];
    }
}
