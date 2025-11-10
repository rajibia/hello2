<?php

namespace App\Http\Requests;

use App\Models\Procedure;
use Illuminate\Foundation\Http\FormRequest;

class CreateProcedureRequest extends FormRequest
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
        return Procedure::$rules;
    }
}
