<?php

namespace App\Models;

use App\Traits\FormatMoney;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Transaction extends Model
{
    use HasFactory;
    use FormatMoney;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'payer_id',
        'payee_id',
        'status_id',
        'value',
    ];

    /**
     * @return BelongsTo
     */
    public function payer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'payer_id');
    }

    /**
     * @return BelongsTo
     */
    public function payee(): BelongsTo
    {
        return $this->belongsTo(User::class, 'payee_id');
    }

    /**
     * @return BelongsTo
     */
    public function status(): BelongsTo
    {
        return $this->belongsTo(Status::class);
    }

    /**
     * @return Attribute
     */
    protected function valueInReal(): Attribute
    {
        return Attribute::make(
            get: fn () => (string) self::convertCentsToReal($this->value ?? 0)
        );
    }
}
