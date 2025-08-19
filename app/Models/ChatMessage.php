<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ChatMessage extends Model
{
    use HasFactory;

    protected $fillable = [
        'service_request_id',
        'sender_id',
        'receiver_id',
        'message',
        'file_path',
        'file_name',
        'file_type',
        'is_read',
        'read_at',
    ];

    protected function casts(): array
    {
        return [
            'is_read' => 'boolean',
            'read_at' => 'datetime',
        ];
    }

    // Relationships
    public function serviceRequest(): BelongsTo
    {
        return $this->belongsTo(ServiceRequest::class);
    }

    public function sender(): BelongsTo
    {
        return $this->belongsTo(User::class, 'sender_id');
    }

    public function receiver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'receiver_id');
    }

    // Scopes
    public function scopeUnread($query)
    {
        return $query->where('is_read', false);
    }

    public function scopeForUser($query, $userId)
    {
        return $query->where(function ($q) use ($userId) {
            $q->where('sender_id', $userId)
              ->orWhere('receiver_id', $userId);
        });
    }

    public function scopeBetweenUsers($query, $user1Id, $user2Id)
    {
        return $query->where(function ($q) use ($user1Id, $user2Id) {
            $q->where(function ($subQ) use ($user1Id, $user2Id) {
                $subQ->where('sender_id', $user1Id)
                     ->where('receiver_id', $user2Id);
            })->orWhere(function ($subQ) use ($user1Id, $user2Id) {
                $subQ->where('sender_id', $user2Id)
                     ->where('receiver_id', $user1Id);
            });
        });
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

    public function hasFile(): bool
    {
        return !is_null($this->file_path);
    }

    public function getFileUrlAttribute(): ?string
    {
        return $this->file_path ? asset('storage/' . $this->file_path) : null;
    }

    public function isImage(): bool
    {
        return $this->hasFile() && in_array($this->file_type, ['jpg', 'jpeg', 'png', 'gif']);
    }

    public function isDocument(): bool
    {
        return $this->hasFile() && in_array($this->file_type, ['pdf', 'doc', 'docx']);
    }

    public function getFormattedTimeAttribute(): string
    {
        return $this->created_at->format('H:i');
    }

    public function getFormattedDateAttribute(): string
    {
        return $this->created_at->format('M d, Y');
    }
}
