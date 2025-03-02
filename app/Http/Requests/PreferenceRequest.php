<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PreferenceRequest extends FormRequest
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
            'sources' => 'nullable|array|min:1',
            'sources.*' => 'string',

            'categories' => 'nullable|array|min:1',
            'categories.*' => 'string',

            'authors' => 'nullable|array|min:1',
            'authors.*' => 'string',
        ];
    }
}
