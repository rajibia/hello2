<?php

namespace App\Http\Requests;

use App\Models\Diagnosis;
use Illuminate\Foundation\Http\FormRequest;

class UpdateDiagnosisRequest extends FormRequest
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
        $rules = Diagnosis::$rules;
        $rules['name'] = 'required|unique:diagnosis,name,'.$this->route('diagnosis')->id;

        return $rules;
    }
}
