<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class KycDocument extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'document_type',
        'document_number',
        'file_path',
        'original_name',
        'file_size',
        'status',
        'rejection_reason',
        'verified_at',
        'verified_by',
    ];

    protected function casts(): array
    {
        return [
            'verified_at' => 'datetime',
        ];
    }

    // Relationships
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function verifiedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'verified_by');
    }

    // Scopes
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    public function scopeRejected($query)
    {
        return $query->where('status', 'rejected');
    }

    // Helper methods
    public function canBeApproved(): bool
    {
        return $this->status === 'pending';
    }

    public function canBeRejected(): bool
    {
        return $this->status === 'pending';
    }

    public function approve(int $verifiedBy): void
    {
        $this->update([
            'status' => 'approved',
            'verified_at' => now(),
            'verified_by' => $verifiedBy,
            'rejection_reason' => null,
        ]);
    }

    public function reject(string $reason, int $verifiedBy): void
    {
        $this->update([
            'status' => 'rejected',
            'rejection_reason' => $reason,
            'verified_by' => $verifiedBy,
        ]);
    }

    public function getFileUrlAttribute(): string
    {
        return asset('storage/' . $this->file_path);
    }

    public function getFileSizeFormattedAttribute(): string
    {
        $bytes = $this->file_size;
        $units = ['B', 'KB', 'MB', 'GB'];
        
        for ($i = 0; $bytes > 1024; $i++) {
            $bytes /= 1024;
        }
        
        return round($bytes, 2) . ' ' . $units[$i];
    }

    public function getStatusBadgeAttribute(): string
    {
        $badges = [
            'pending' => 'badge-warning',
            'approved' => 'badge-success',
            'rejected' => 'badge-danger',
        ];

        return $badges[$this->status] ?? 'badge-secondary';
    }

    public function getDocumentTypeDisplayAttribute(): string
    {
        $types = [
            'citizenship' => 'Citizenship Certificate',
            'license' => 'Driving License',
            'passport' => 'Passport',
            'other' => 'Other Document',
        ];

        return $types[$this->document_type] ?? 'Unknown';
    }
}
