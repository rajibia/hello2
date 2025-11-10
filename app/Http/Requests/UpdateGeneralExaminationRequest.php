<?php

namespace App\Http\Requests;

use App\Models\GeneralExamination;
use Illuminate\Foundation\Http\FormRequest;

class UpdateGeneralExaminationRequest extends FormRequest
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
        $rules = GeneralExamination::$rules;

        return $rules;
    }
}
