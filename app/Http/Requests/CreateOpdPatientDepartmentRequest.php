<?php

namespace App\Http\Requests;

use App\Models\OpdPatientDepartment;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Carbon;
use Illuminate\Validation\Rule;

class CreateOpdPatientDepartmentRequest extends FormRequest
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
        // Get the default rules defined on the model
        $defaultRules = OpdPatientDepartment::$rules;

        // --- Custom Duplicate Check Rule ---

        // Check if patient_id and appointment_date are present before setting the rule
        if (isset($this->patient_id) && isset($this->appointment_date)) {
            
            // Try to parse the date safely
            try {
                // Ensure we only check against the date part (Y-m-d)
                $appointmentDateOnly = Carbon::parse($this->appointment_date)->toDateString();
            } catch (\Exception $e) {
                // If date parsing fails, use a fallback, though validation should handle date format errors separately.
                $appointmentDateOnly = $this->appointment_date; 
            }

            // 2. Add a custom unique rule for the patient_id AND the appointment_date
            // Rule::unique checks if the patient_id is unique *only* where the appointment_date matches the calculated date.
            // Note: If patient_id is already an array in $defaultRules, array_merge handles it.
            $defaultRules['patient_id'] = array_merge((array)$defaultRules['patient_id'], [
                Rule::unique('opd_patient_departments')->where(function ($query) use ($appointmentDateOnly) {
                    return $query->whereDate('appointment_date', $appointmentDateOnly);
                }),
            ]);
        }
        
        return $defaultRules;
    }

    public function messages(): array
    {
        return [
            'case_id.required' => __('messages.ipd_patient.the_case_field_is_required'),
            'bed_id.required' => __('messages.ipd_patient.the_bed_field_is_required'),
            
            // --- Custom message for the duplicate patient check ---
            'patient_id.unique' => 'Patient is already added! They have an OPD appointment scheduled for the selected date.',
        ];
    }
}