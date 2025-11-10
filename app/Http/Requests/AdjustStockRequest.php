<?php

namespace App\Http\Requests;

use App\Models\Medicine;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class AdjustStockRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation()
    {
        $this->sanitize();
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        $rules['available_quantity'] = 'required|numeric';
        $rules['store_quantity'] = 'required|numeric';
        
        return $rules;
    }

    public function messages(): array
    {
        return [
            'transfer_from.required' => 'Transfer From is required',
            'transfer_to.required' => 'Transfer To is required',
            'transfer_quantity.required' => 'Transfer Quantity is required',
        ];
    }

    public function sanitize()
    {
        $input = $this->all();
        // $input['selling_price'] = ! empty($input['selling_price']) ? str_replace(',', '',
        //     $input['selling_price']) : null;
        // $input['buying_price'] = ! empty($input['buying_price']) ? str_replace(',', '', $input['buying_price']) : null;
        // $this->replace($input);
    }
}
