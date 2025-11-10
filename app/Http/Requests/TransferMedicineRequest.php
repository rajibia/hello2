<?php

namespace App\Http\Requests;

use App\Models\Medicine;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class TransferMedicineRequest extends FormRequest
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
        // $rules['transfer_from'] = 'required';
        // $rules['transfer_to'] = 'required';
        // $rules['transfer_quantity'] = 'required';
        return [
            'transfer_from' => 'required',
            'transfer_to' => [
                'required',
                function ($attribute, $value, $fail) {
                    $transferFrom = $this->input('transfer_from');
    
                    if ($value === $transferFrom) {
                        $fail('Transfer To cannot be the same as Transfer From.');
                    }
                },
            ],
            'transfer_quantity' => [
                'required',
                'numeric',
                'min:1',
                function ($attribute, $value, $fail) {
                    $transferFrom = $this->input('transfer_from');
    
                    // Check the quantity based on transfer_from type
                    if ($transferFrom === 'Dispensary') {
                        $quantity = (int)$this->input('quantity');
                    } elseif ($transferFrom === 'Store') {
                        $quantity = (int)$this->input('store_quantity');
                    } else {
                        // Handle other cases if needed
                        $quantity = 0;
                    }
    
                    if ($quantity < $value) {
                        $fail("Transfer From quantity ({$quantity}) must be greater than or equal to Transfer Quantity.");
                    }
                },
            ],
        ];
        // return $rules;
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
