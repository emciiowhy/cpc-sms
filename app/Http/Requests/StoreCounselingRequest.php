<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreCounselingRequest extends FormRequest
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
            'course'       => 'nullable|string|max:255',
            'year_level'   => 'nullable|integer|between:1,4',
            'category'     => 'required|in:academic,personal,behavioral,career',
            'concern'      => 'required|string',
            'action_taken' => 'nullable|string',
            'status'       => 'required|in:open,ongoing,resolved',
            'session_date' => 'required|date',
        ];
    }
}