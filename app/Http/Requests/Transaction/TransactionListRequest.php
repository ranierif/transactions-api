<?php

namespace App\Http\Requests\Transaction;

use App\Enums\Status;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;

class TransactionListRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules(): array
    {
        return [
            'payer_id' => ['nullable', 'integer', 'exists:users,id'],
            'payee_id' => ['nullable', 'integer', 'exists:users,id'],
            'status_id' => ['nullable', 'integer', new Enum(Status::class)],
        ];
    }
}
