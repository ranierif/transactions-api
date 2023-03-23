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
            'payer' => UserResource::make($this->payer),
            'payee' => UserResource::make($this->payee),
            'value' => $this->resource->valueInReal,
            'status' => $this->resource->status->title,
            'created_at' => $this->resource->created_at->format('Y-m-d H:i:s'),
        ];
    }
}
