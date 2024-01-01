<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SimpleCatalogueRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'catalogue.required'  => 'A catalogue name is required',
            'catalogue.ends_with' => 'It is required a catalogue suffix in order to work with a catalogue table. A valid suffix is: ' . env('CATALOGUE_SUFFIX'),
        ];
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'catalogue' => 'required|ends_with:' . env('CATALOGUE_SUFFIX'),
        ];
    }
}
