<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ServiceRequest extends Model
{
    use HasUlids, SoftDeletes;

    protected $table = 'requests';

    protected $fillable = [
        'user_id',
        'title',
        'description',
        'image',
        'pricing_type',
        'budget',
        'currency_code',
        'published_at',
        'expires_at',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'budget' => 'decimal:2',
            'published_at' => 'datetime',
            'expires_at' => 'datetime',
            'applicant_count' => 'integer',
            'views_count' => 'integer',
            'bookmarks_count' => 'integer',
            'popularity_score' => 'decimal:2',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function categories(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(Category::class, 'category_request', 'request_id', 'category_id')->withTimestamps();
    }

    public function offers(): HasMany
    {
        return $this->hasMany(Offer::class, 'request_id');
    }
}
