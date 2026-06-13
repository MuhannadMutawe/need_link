<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model
{
    use HasFactory, HasUlids;

    protected $fillable = [
        'request_id',
        'offer_id',
        'client_id',
        'provider_id',
        'agreed_price',
        'currency_code',
        'order_type',
        'status',
        'is_paid',
        'deadline_at',
        'started_at',
        'completed_at',
        'confirm_deadline_at',
        'revision_count',
        'cancelled_by',
        'cancellation_reason',
        'closed_by',
        // Product shipping fields
        'carrier',
        'tracking_number',
        'tracking_url',
        'is_shipped',
        'shipped_at',
    ];

    protected $casts = [
        'agreed_price' => 'decimal:2',
        'is_paid' => 'boolean',
        'is_shipped' => 'boolean',
        'deadline_at' => 'datetime',
        'started_at' => 'datetime',
        'completed_at' => 'datetime',
        'confirm_deadline_at' => 'datetime',
        'shipped_at' => 'datetime',
    ];

    public function serviceRequest(): BelongsTo
    {
        return $this->belongsTo(ServiceRequest::class, 'request_id');
    }

    public function offer(): BelongsTo
    {
        return $this->belongsTo(Offer::class);
    }

    public function client(): BelongsTo
    {
        return $this->belongsTo(User::class, 'client_id');
    }

    public function provider(): BelongsTo
    {
        return $this->belongsTo(User::class, 'provider_id');
    }

    public function cancelledBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'cancelled_by');
    }

    public function closedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'closed_by');
    }

    public function deliveries(): HasMany
    {
        return $this->hasMany(OrderDelivery::class)->orderBy('created_at', 'asc');
    }

    public function revisions(): HasMany
    {
        return $this->hasMany(OrderRevision::class)->orderBy('created_at', 'asc');
    }

    public function cancellationRequests(): HasMany
    {
        return $this->hasMany(OrderCancellationRequest::class)->orderBy('created_at', 'asc');
    }

    public function disputes(): HasMany
    {
        return $this->hasMany(OrderDispute::class)->orderBy('created_at', 'asc');
    }
}
