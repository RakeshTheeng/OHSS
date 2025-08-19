<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Notification extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'type',
        'title',
        'message',
        'data',
        'is_read',
        'read_at',
    ];

    protected function casts(): array
    {
        return [
            'data' => 'array',
            'is_read' => 'boolean',
            'read_at' => 'datetime',
        ];
    }

    // Relationships
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // Scopes
    public function scopeUnread($query)
    {
        return $query->where('is_read', false);
    }

    public function scopeRead($query)
    {
        return $query->where('is_read', true);
    }

    public function scopeOfType($query, string $type)
    {
        return $query->where('type', $type);
    }

    // Helper methods
    public function markAsRead(): void
    {
        if (!$this->is_read) {
            $this->update([
                'is_read' => true,
                'read_at' => now(),
            ]);
        }
    }

    public function markAsUnread(): void
    {
        $this->update([
            'is_read' => false,
            'read_at' => null,
        ]);
    }

    public function getIconAttribute(): string
    {
        $icons = [
            'service_request' => 'fas fa-tools',
            'booking_confirmed' => 'fas fa-calendar-check',
            'payment_received' => 'fas fa-money-bill-wave',
            'review_received' => 'fas fa-star',
            'message_received' => 'fas fa-comment',
            'provider_approved' => 'fas fa-check-circle',
            'provider_rejected' => 'fas fa-times-circle',
            'booking_cancelled' => 'fas fa-ban',
            'service_completed' => 'fas fa-check-double',
        ];

        return $icons[$this->type] ?? 'fas fa-bell';
    }

    public function getTimeAgoAttribute(): string
    {
        return $this->created_at->diffForHumans();
    }

    // Static methods for creating notifications
    public static function createForUser(int $userId, string $type, string $title, string $message, array $data = []): self
    {
        return self::create([
            'user_id' => $userId,
            'type' => $type,
            'title' => $title,
            'message' => $message,
            'data' => $data,
        ]);
    }

    public static function serviceRequestReceived(int $providerId, ServiceRequest $request): self
    {
        return self::createForUser(
            $providerId,
            'service_request',
            'New Service Request',
            "You have received a new service request from {$request->customer->name}",
            ['service_request_id' => $request->id]
        );
    }

    public static function bookingConfirmed(int $customerId, Booking $booking): self
    {
        return self::createForUser(
            $customerId,
            'booking_confirmed',
            'Booking Confirmed',
            "Your booking with {$booking->provider->name} has been confirmed",
            ['booking_id' => $booking->id]
        );
    }

    public static function paymentReceived(int $providerId, Payment $payment): self
    {
        return self::createForUser(
            $providerId,
            'payment_received',
            'Payment Received',
            "You have received a payment of {$payment->formatted_amount}",
            ['payment_id' => $payment->id]
        );
    }

    public static function reviewReceived(int $providerId, Review $review): self
    {
        return self::createForUser(
            $providerId,
            'review_received',
            'New Review',
            "You have received a {$review->rating}-star review from {$review->customer->name}",
            ['review_id' => $review->id]
        );
    }

    public static function serviceRequestAccepted(int $customerId, ServiceRequest $request): self
    {
        return self::createForUser(
            $customerId,
            'service_request_accepted',
            'Request Accepted!',
            "Your service request has been accepted by {$request->provider->name}. You can now proceed to book the service.",
            ['service_request_id' => $request->id]
        );
    }

    public static function bookingReminder(int $customerId, Booking $booking): self
    {
        return self::createForUser(
            $customerId,
            'booking_reminder',
            'Service Reminder',
            "Your service with {$booking->provider->name} is scheduled for tomorrow at {$booking->scheduled_date->format('h:i A')}",
            ['booking_id' => $booking->id]
        );
    }

    public static function messageReceived(int $receiverId, $sender, Chat $chat): self
    {
        return self::createForUser(
            $receiverId,
            'message_received',
            'New Message',
            "You have a new message from {$sender->name}",
            ['chat_id' => $chat->id]
        );
    }
}
