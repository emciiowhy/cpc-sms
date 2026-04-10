<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateEnrollmentRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        // Must be true to allow the update to proceed
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
            
            // This allows the current record to keep its student_id without triggering a "unique" error
            'student_id'   => 'required|string|max:50|unique:enrollments,student_id,' . $this->route('enrollment')->id,
            
            'email'        => 'nullable|email|max:255',
            'course'       => 'required|string|max:255',
            'year_level'   => 'required|integer|between:1,4',
            'section'      => 'nullable|string|max:50',
            'status'       => 'required|in:pending,approved,rejected',
            'remarks'      => 'nullable|string',
        ];
    }
}