<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateClinicVisitRequest extends FormRequest
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
            'student_name' => 'required|string|max:255',
            'student_id'   => 'required|string|max:50',
            'complaint'    => 'required|string|max:255',
            'treatment'    => 'nullable|string|max:255',
            'status'       => 'required|in:treated,referred,monitoring',
            'visit_date'   => 'required|date',
            'visit_time'   => 'required',
            'notes'        => 'nullable|string',
        ];
    }
}