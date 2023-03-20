<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Chargeback extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'origin_transaction_id',
        'reversal_transaction_id',
        'reason',
    ];

    /**
     * @return BelongsTo
     */
    public function originTransaction(): BelongsTo
    {
        return $this->belongsTo(Transaction::class);
    }

    /**
     * @return BelongsTo
     */
    public function reversalTransaction(): BelongsTo
    {
        return $this->belongsTo(Transaction::class);
    }
}
