<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreBookRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->hasRole('admin');
    }

    public function rules(): array
    {
        return [
            'title' => 'required|string|max:255',
            'author' => 'required|string|max:255',
            'isbn' => 'required|unique:books|max:255',
            'description' => 'nullable|string',
            'total_copies' => 'required|integer|min:1',
        ];
    }

    public function messages(): array
    {
        return [
            'isbn.unique' => 'This ISBN is already in use.',
            'total_copies.min' => 'At least one copy must be added.',
        ];
    }
}