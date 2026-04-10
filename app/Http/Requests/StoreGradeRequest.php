<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreGradeRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        // Set to true so the request isn't blocked
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'student_name' => 'required|string|max:255',
            'student_id'   => 'required|string|max:50',
            'subject'      => 'required|string|max:255',
            'course'       => 'nullable|string|max:255',
            'year_level'   => 'nullable|integer|between:1,4',
            'midterm'      => 'required|numeric|min:0|max:100',
            'finals'       => 'required|numeric|min:0|max:100',
        ];
    }
}