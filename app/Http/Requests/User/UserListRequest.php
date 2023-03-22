<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;

class UserListRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules(): array
    {
        return [
            'email' => ['nullable', 'email'],
            'name' => ['nullable', 'string', 'max:255'],
            'id' => ['nullable', 'integer', 'exists:users,id'],
            'document_number' => ['nullable', 'string', 'exists:users,document_number'],
            'document_type_id' => ['nullable', 'integer', 'exists:document_types,id'],
        ];
    }
}
