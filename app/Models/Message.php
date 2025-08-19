<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Message extends Model
{
    use HasFactory;

    protected $fillable = [
        'chat_id',
        'sender_id',
        'message',
        'file_path',
        'file_name',
        'file_type',
        'read_at',
    ];

    protected function casts(): array
    {
        return [
            'read_at' => 'datetime',
        ];
    }

    // Relationships
    public function chat(): BelongsTo
    {
        return $this->belongsTo(Chat::class);
    }

    public function sender(): BelongsTo
    {
        return $this->belongsTo(User::class, 'sender_id');
    }

    // Scopes
    public function scopeUnread($query)
    {
        return $query->whereNull('read_at');
    }

    public function scopeRead($query)
    {
        return $query->whereNotNull('read_at');
    }

    // Helper methods
    public function markAsRead(): void
    {
        if (is_null($this->read_at)) {
            $this->update(['read_at' => now()]);
        }
    }

    public function isRead(): bool
    {
        return !is_null($this->read_at);
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
