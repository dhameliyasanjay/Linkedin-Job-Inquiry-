<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePositionRequest extends FormRequest
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
        $position = $this->route('position');
        $id = is_object($position) ? $position->id : $position;

        return [
            'name' => 'required|unique:positions,name,' . $id . '|max:255',
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
            'name.required' => 'The position name is required.',
            'name.unique' => 'This position name already exists.',
            'name.max' => 'The position name must not exceed 255 characters.',
        ];
    }
}
