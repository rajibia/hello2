<?php

namespace App\Http\Requests;

use App\Models\RadiologyParameter;
use Illuminate\Foundation\Http\FormRequest;

class UpdateRadiologyParameterRequest extends FormRequest
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
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        $rules = RadiologyParameter::$rules;
        $rules['parameter_name'] = 'required|unique:radiology_parameters,parameter_name,'.$this->route('radiologyParameter')->id;

        return $rules;
    }
}
