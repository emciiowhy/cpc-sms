<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateAppointmentRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        // Allow the request to proceed
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'student_name'     => 'required|string|max:255',
            'student_id'       => 'required|string|max:50',
            'purpose'          => 'required|string|max:255',
            'appointment_date' => 'required|date',
            'appointment_time' => 'required',
            'status'           => 'required|in:pending,approved,done',
            'notes'            => 'nullable|string',
        ];
    }
}