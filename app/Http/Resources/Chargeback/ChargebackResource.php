<?php

namespace App\Http\Resources\Chargeback;

use App\Http\Resources\Transaction\TransactionResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ChargebackResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'origin_transaction' => new TransactionResource($this->originTransaction),
            'reversal_transaction' => new TransactionResource($this->reversalTransaction),
            'reason' => $this->reason,
            'created_at' => $this->created_at->format('Y-m-d H:i:s'),
        ];
    }
}
