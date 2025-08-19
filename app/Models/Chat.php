<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Chat extends Model
{
    use HasFactory;

    protected $fillable = [
        'booking_id',
        'customer_id',
        'provider_id',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
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

    public function messages(): HasMany
    {
        return $this->hasMany(Message::class);
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeForUser($query, $userId)
    {
        return $query->where(function ($q) use ($userId) {
            $q->where('customer_id', $userId)
              ->orWhere('provider_id', $userId);
        });
    }

    // Helper methods
    public function getOtherParticipant($userId)
    {
        return $this->customer_id === $userId ? $this->provider : $this->customer;
    }

    public function hasUnreadMessagesFor($userId)
    {
        return $this->messages()
                   ->where('sender_id', '!=', $userId)
                   ->whereNull('read_at')
                   ->exists();
    }

    public function getUnreadCountFor($userId)
    {
        return $this->messages()
                   ->where('sender_id', '!=', $userId)
                   ->whereNull('read_at')
                   ->count();
    }

    public function getLastMessage()
    {
        return $this->messages()->latest()->first();
    }

    public function markMessagesAsReadFor($userId)
    {
        $this->messages()
             ->where('sender_id', '!=', $userId)
             ->whereNull('read_at')
             ->update(['read_at' => now()]);
    }
}
