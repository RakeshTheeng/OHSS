<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class ServiceRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_id',
        'provider_id',
        'service_category_id',
        'title',
        'description',
        'address',
        'latitude',
        'longitude',
        'preferred_date',
        'preferred_time',
        'estimated_duration',
        'estimated_price',
        'budget_min',
        'budget_max',
        'required_hours',
        'hourly_rate',
        'total_budget',
        'urgency',
        'additional_notes',
        'status',
        'provider_response',
        'responded_at',
    ];

    protected function casts(): array
    {
        return [
            'preferred_date' => 'datetime',
            'responded_at' => 'datetime',
            'estimated_price' => 'decimal:2',
            'budget_min' => 'decimal:2',
            'budget_max' => 'decimal:2',
            'required_hours' => 'decimal:2',
            'hourly_rate' => 'decimal:2',
            'total_budget' => 'decimal:2',
            'latitude' => 'decimal:8',
            'longitude' => 'decimal:8',
        ];
    }

    // Relationships
    public function customer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'customer_id');
    }

    public function provider(): BelongsTo
    {
        return $this->belongsTo(User::class, 'provider_id');
    }

    public function serviceCategory(): BelongsTo
    {
        return $this->belongsTo(ServiceCategory::class);
    }

    public function booking(): HasOne
    {
        return $this->hasOne(Booking::class);
    }

    public function chatMessages(): HasMany
    {
        return $this->hasMany(ChatMessage::class);
    }

    // Scopes
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeAccepted($query)
    {
        return $query->where('status', 'accepted');
    }

    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    // Helper methods
    public function canBeAccepted(): bool
    {
        return $this->status === 'pending';
    }

    public function canBeRejected(): bool
    {
        return $this->status === 'pending';
    }

    public function canBeBooked(): bool
    {
        return $this->status === 'accepted';
    }

    public function accept(string $response = null): void
    {
        $this->update([
            'status' => 'accepted',
            'provider_response' => $response,
            'responded_at' => now(),
        ]);
    }

    public function reject(string $response): void
    {
        $this->update([
            'status' => 'rejected',
            'provider_response' => $response,
            'responded_at' => now(),
        ]);
    }

    public function getStatusBadgeAttribute(): string
    {
        $badges = [
            'pending' => 'badge-warning',
            'accepted' => 'badge-success',
            'rejected' => 'badge-danger',
            'booked' => 'badge-info',
            'in_progress' => 'badge-primary',
            'completed' => 'badge-success',
            'cancelled' => 'badge-secondary',
        ];

        return $badges[$this->status] ?? 'badge-secondary';
    }
}
