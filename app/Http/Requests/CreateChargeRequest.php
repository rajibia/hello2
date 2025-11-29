<?php

namespace App\Http\Requests;

use App\Models\Charge;
use App\Models\ChargeType;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CreateChargeRequest extends FormRequest
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
        $rules = Charge::$rules;

        // Restrict charge_type to allowed types (Procedures, Investigations, Others)
        $allowedNames = ['Procedures', 'Investigations', 'Others'];
        $allowedIds = ChargeType::whereIn('name', $allowedNames)->where('status', 1)->pluck('id')->toArray();

        if (!empty($allowedIds)) {
            $rules['charge_type'] = ['required', Rule::in($allowedIds)];
        } else {
            // Fallback: require charge_type if no matching types found
            $rules['charge_type'] = 'required';
        }

        return $rules;
    }
}
