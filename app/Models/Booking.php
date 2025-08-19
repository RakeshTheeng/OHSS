<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Booking extends Model
{
    use HasFactory;

    protected $fillable = [
        'service_request_id',
        'customer_id',
        'provider_id',
        'scheduled_date',
        'duration',
        'total_amount',
        'payment_method',
        'payment_status',
        'status',
        'special_instructions',
        'started_at',
        'completed_at',
        'completion_notes',
    ];

    protected function casts(): array
    {
        return [
            'scheduled_date' => 'datetime',
            'started_at' => 'datetime',
            'completed_at' => 'datetime',
            'total_amount' => 'decimal:2',
        ];
    }

    // Relationships
    public function serviceRequest(): BelongsTo
    {
        return $this->belongsTo(ServiceRequest::class);
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'customer_id');
    }

    public function provider(): BelongsTo
    {
        return $this->belongsTo(User::class, 'provider_id');
    }

    public function payment(): HasOne
    {
        return $this->hasOne(Payment::class);
    }

    public function review(): HasOne
    {
        return $this->hasOne(Review::class);
    }

    public function chat(): HasOne
    {
        return $this->hasOne(Chat::class);
    }

    // Scopes
    public function scopeConfirmed($query)
    {
        return $query->where('status', 'confirmed');
    }

    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    public function scopeUpcoming($query)
    {
        return $query->where('scheduled_date', '>', now())
                    ->where('status', 'confirmed');
    }

    // Helper methods
    public function canBeStarted(): bool
    {
        return $this->status === 'confirmed' && 
               $this->scheduled_date <= now();
    }

    public function canBeCompleted(): bool
    {
        return $this->status === 'in_progress';
    }

    public function canBeCancelled(): bool
    {
        return in_array($this->status, ['confirmed', 'in_progress']);
    }

    public function start(): void
    {
        $this->update([
            'status' => 'in_progress',
            'started_at' => now(),
        ]);

        $this->serviceRequest->update(['status' => 'in_progress']);
    }

    public function complete(string $notes = null): void
    {
        $this->update([
            'status' => 'completed',
            'completed_at' => now(),
            'completion_notes' => $notes,
        ]);

        $this->serviceRequest->update(['status' => 'completed']);
    }

    public function cancel(): void
    {
        $this->update(['status' => 'cancelled']);
        $this->serviceRequest->update(['status' => 'cancelled']);
    }

    public function getDurationInHoursAttribute(): float
    {
        return $this->duration / 60;
    }

    public function getStatusBadgeAttribute(): string
    {
        $badges = [
            'pending_payment' => 'badge-warning',
            'confirmed' => 'badge-info',
            'in_progress' => 'badge-primary',
            'completed' => 'badge-success',
            'cancelled' => 'badge-danger',
        ];

        return $badges[$this->status] ?? 'badge-secondary';
    }
}
