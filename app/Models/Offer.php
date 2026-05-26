<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Offer extends Model
{
    use HasUlids;

    protected $fillable = [
        'request_id',
        'user_id',
        'message',
        'proposed_price',
        'currency_code',
        'estimated_time',
        'time_unit',
        'expires_at',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'proposed_price' => 'decimal:2',
            'estimated_time' => 'integer',
            'expires_at' => 'datetime',
        ];
    }

    public function serviceRequest(): BelongsTo
    {
        return $this->belongsTo(ServiceRequest::class, 'request_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
