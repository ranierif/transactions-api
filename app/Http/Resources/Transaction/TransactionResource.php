<?php

namespace App\Http\Resources\Transaction;

use App\Http\Resources\User\UserResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TransactionResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'payer' => new UserResource($this->payer),
            'payee' => new UserResource($this->payee),
            'value' => $this->valueInReal(),
            'status' => $this->status->title,
            'created_at' => $this->created_at->format('Y-m-d H:i:s'),
        ];
    }
}
