<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePermissionRequest extends FormRequest
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
            'name' => 'required|string|max:30|unique:permissions,name'
        ];
    }

    public function messages (): array
    {
        return [
            'name.required' => 'Ingrese el nombre',
            'name.string' => 'El nombre debe de ser texto',
            'name.max' => 'El nombre no puede contener mas de 30 caracteres',
            'name.unique' => 'El nombre ingresado ya existe',
        ];
    }
}
