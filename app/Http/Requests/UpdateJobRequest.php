<?php

namespace App\Http\Requests;

use App\Models\Job;
use Illuminate\Foundation\Http\FormRequest;

class UpdateJobRequest extends FormRequest
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
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name'          => 'required|string|max:255',
            'position_id'   => 'required|exists:positions,id',
            'state'         => 'required|string|max:255',
            'city'          => 'required|string|max:255',
            'experience'    => 'nullable|string|max:255',
            'start_date'    => 'required|date',
            'plan_duration' => ['required', 'string', 'in:' . implode(',', array_keys(Job::PLAN_DURATIONS))],
            // end_date is computed server-side; accept it if sent but don't require it
            'end_date'      => 'nullable|date',
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'name.required'          => 'The job name is required.',
            'position_id.required'   => 'Please select a position.',
            'position_id.exists'     => 'The selected position is invalid.',
            'state.required'         => 'Please select a state.',
            'city.required'          => 'Please select a city.',
            'start_date.required'    => 'The start date is required.',
            'start_date.date'        => 'Please enter a valid start date.',
            'plan_duration.required' => 'Please select a plan duration.',
            'plan_duration.in'       => 'The selected plan duration is invalid.',
        ];
    }
}
