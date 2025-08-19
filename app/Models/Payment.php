<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'booking_id',
        'customer_id',
        'provider_id',
        'amount',
        'payment_method',
        'status',
        'transaction_id',
        'esewa_ref_id',
        'gateway_response',
        'paid_at',
        'failure_reason',
        'refund_amount',
        'refunded_at',
        'refund_reason',
    ];

    protected function casts(): array
    {
        return [
            'amount' => 'decimal:2',
            'refund_amount' => 'decimal:2',
            'paid_at' => 'datetime',
            'refunded_at' => 'datetime',
            'gateway_response' => 'array',
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
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    public function scopeFailed($query)
    {
        return $query->where('status', 'failed');
    }

    public function scopeRefunded($query)
    {
        return $query->where('status', 'refunded');
    }

    // Helper methods
    public function canBeRefunded(): bool
    {
        return $this->status === 'completed' && is_null($this->refunded_at);
    }

    public function markAsPaid(string $transactionId = null, array $gatewayResponse = null): void
    {
        $this->update([
            'status' => 'completed',
            'transaction_id' => $transactionId,
            'gateway_response' => $gatewayResponse,
            'paid_at' => now(),
        ]);

        $this->booking->update(['payment_status' => 'paid']);
    }

    public function markAsFailed(string $reason): void
    {
        $this->update([
            'status' => 'failed',
            'failure_reason' => $reason,
        ]);

        $this->booking->update(['payment_status' => 'failed']);
    }

    public function refund(float $amount, string $reason): void
    {
        $this->update([
            'status' => 'refunded',
            'refund_amount' => $amount,
            'refund_reason' => $reason,
            'refunded_at' => now(),
        ]);

        $this->booking->update(['payment_status' => 'refunded']);
    }

    public function isEsewa(): bool
    {
        return $this->payment_method === 'esewa';
    }

    public function isCash(): bool
    {
        return $this->payment_method === 'cash';
    }

    public function getStatusBadgeAttribute(): string
    {
        $badges = [
            'awaiting_payment' => 'badge-warning',
            'pending' => 'badge-warning',
            'processing' => 'badge-info',
            'completed' => 'badge-success',
            'failed' => 'badge-danger',
            'refunded' => 'badge-secondary',
        ];

        return $badges[$this->status] ?? 'badge-secondary';
    }

    public function getFormattedAmountAttribute(): string
    {
        return 'Rs. ' . number_format($this->amount, 2);
    }
}
