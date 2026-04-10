<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreEnrollmentRequest extends FormRequest // OR UpdateEnrollmentRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        // Change to true to allow the request to proceed
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
            'student_id'   => [
                'required',
                'string',
                'max:50',
                // This ensures student_id is unique in the 'enrollments' table
                $this->isMethod('POST') 
                    ? 'unique:enrollments,student_id' 
                    : Rule::unique('enrollments', 'student_id')->ignore($this->enrollment),
            ],
            'email'        => 'nullable|email|max:255',
            'course'       => 'required|string|max:255',
            'year_level'   => 'required|integer|between:1,4',
            'section'      => 'nullable|string|max:50',
            'status'       => 'required|in:pending,approved,rejected',
            'remarks'      => 'nullable|string',
        ];
    }
}