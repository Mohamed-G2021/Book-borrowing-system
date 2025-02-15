<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateBookRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->hasRole('admin');
    }

    public function rules(): array
    {
        $bookId = $this->route('book')->id;

        return [
            'title' => 'required|string|max:255',
            'author' => 'required|string|max:255',
            'isbn' => [
                'required',
                'max:255',
                Rule::unique('books')->ignore($bookId),
            ],
            'description' => 'nullable|string',
            'total_copies' => 'required|integer|min:1',
        ];
    }

    public function messages(): array
    {
        return [
            'isbn.unique' => 'This ISBN is already in use by another book.',
            'total_copies.min' => 'At least one copy must be added.',
        ];
    }
}