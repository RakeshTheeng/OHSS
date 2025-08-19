<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Review extends Model
{
    use HasFactory;

    protected $fillable = [
        'booking_id',
        'customer_id',
        'provider_id',
        'rating',
        'comment',
        'provider_response',
        'provider_responded_at',
        'is_flagged',
        'flag_reason',
        'is_approved',
    ];

    protected function casts(): array
    {
        return [
            'provider_responded_at' => 'datetime',
            'is_flagged' => 'boolean',
            'is_approved' => 'boolean',
        ];
    }

    // Relationships
    public function booking(): BelongsTo
    {
        return $this->belongsTo(Booking::class);
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'customer_id');
    }

    public function provider(): BelongsTo
    {
        return $this->belongsTo(User::class, 'provider_id');
    }

    // Scopes
    public function scopeApproved($query)
    {
        return $query->where('is_approved', true);
    }

    public function scopeFlagged($query)
    {
        return $query->where('is_flagged', true);
    }

    public function scopeWithResponse($query)
    {
        return $query->whereNotNull('provider_response');
    }

    // Helper methods
    public function canBeResponded(): bool
    {
        return is_null($this->provider_response);
    }

    public function respond(string $response): void
    {
        $this->update([
            'provider_response' => $response,
            'provider_responded_at' => now(),
        ]);
    }

    public function flag(string $reason): void
    {
        $this->update([
            'is_flagged' => true,
            'flag_reason' => $reason,
        ]);
    }

    public function approve(): void
    {
        $this->update([
            'is_approved' => true,
            'is_flagged' => false,
            'flag_reason' => null,
        ]);
    }

    public function reject(): void
    {
        $this->update(['is_approved' => false]);
    }

    public function getStarsAttribute(): string
    {
        return str_repeat('★', $this->rating) . str_repeat('☆', 5 - $this->rating);
    }

    public function getRatingBadgeAttribute(): string
    {
        if ($this->rating >= 4) return 'badge-success';
        if ($this->rating >= 3) return 'badge-warning';
        return 'badge-danger';
    }

    // Update provider rating after review changes
    protected static function booted()
    {
        static::created(function ($review) {
            $review->provider->updateRating();
        });

        static::updated(function ($review) {
            $review->provider->updateRating();
        });

        static::deleted(function ($review) {
            $review->provider->updateRating();
        });
    }
}
