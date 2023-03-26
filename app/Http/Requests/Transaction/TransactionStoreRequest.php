<?php

namespace App\Http\Requests\Transaction;

use Illuminate\Foundation\Http\FormRequest;

class TransactionStoreRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules(): array
    {
        return [
            'payer_id' => ['required', 'integer', 'exists:users,id'],
            'payee_id' => ['required', 'integer', 'exists:users,id'],
            'value' => ['required', 'numeric', 'gt:0'],
        ];
    }
}
