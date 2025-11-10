<?php

namespace App\Http\Requests;

use App\Models\RadiologyUnit;
use Illuminate\Foundation\Http\FormRequest;

class UpdateRadiologyUnitRequest extends FormRequest
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
        $rules = RadiologyUnit::$rules;
        $rules['name'] = 'required|unique:radiology_units,name,'.$this->route('radiologyUnit')->id;

        return $rules;
    }
}
