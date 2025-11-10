<?php

namespace App\Http\Requests;

use App\Models\RadiologyTestTemplate;
use Illuminate\Foundation\Http\FormRequest;

class UpdateRadiologyTestTemplateRequest extends FormRequest
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
        $rules = RadiologyTestTemplate::$rules;
        $rules['test_name'] = 'required|unique:radiology_test_templates,test_name,' . $this->route('radiologyTest')->id;

        return $rules;
    }
}
