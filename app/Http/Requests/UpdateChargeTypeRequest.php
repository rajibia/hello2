<?php

namespace App\Http\Requests;

use App\Models\ChargeType;
use Illuminate\Foundation\Http\FormRequest;

class UpdateChargeTypeRequest extends FormRequest
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
        $rules = ChargeType::$rules;
        $rules['name'] = 'required|unique:charge_types,name,'.$this->route('charge_type')->id;

        return $rules;
    }
}
