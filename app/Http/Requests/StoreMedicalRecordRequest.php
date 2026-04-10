<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreMedicalRecordRequest extends FormRequest
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
        return [
            'student_name'    => 'required|string|max:255',
            'student_id'      => 'required|string|max:50',
            'course'          => 'nullable|string|max:255',
            'year_level'      => 'nullable|integer|between:1,4',
            'medical_history' => 'nullable|string',
            'allergies'       => 'nullable|string',
            'blood_type'      => 'nullable|string|max:10',
            'height'          => 'nullable|numeric',
            'weight'          => 'nullable|numeric',
            'status'          => 'required|in:fit,not_fit',
            'attachment'      => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
        ];
    }
}