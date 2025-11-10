<?php

namespace App\Http\Requests;
use Illuminate\Foundation\Http\FormRequest;

class StoreShiftRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'shift_name' => 'required|string|max:255',
            'shift_start' => 'required|date_format:H:i',
            'shift_end' => 'required|date_format:H:i',
            'break_duration' => 'required|integer|min:0',
        ];
    }
}
