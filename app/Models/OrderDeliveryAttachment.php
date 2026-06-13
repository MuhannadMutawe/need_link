<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OrderDeliveryAttachment extends Model
{
    use HasFactory, HasUlids;

    protected $fillable = [
        'delivery_id',
        'file_path',
        'file_name',
    ];

    public function delivery(): BelongsTo
    {
        return $this->belongsTo(OrderDelivery::class, 'delivery_id');
    }
}
