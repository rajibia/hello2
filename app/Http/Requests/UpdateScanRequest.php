<?php

namespace App\Http\Requests;

use App\Models\Scan;
use Illuminate\Foundation\Http\FormRequest;

class UpdateScanRequest extends FormRequest
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
        $rules = Scan::$rules;
        $rules['name'] = 'required|unique:scans,name,'.$this->route('scan')->id;

        return $rules;
    }
}
