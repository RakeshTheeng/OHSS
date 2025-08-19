<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'phone',
        'address',
        'latitude',
        'longitude',
        'profile_image',
        'status',
        'services',
        'hourly_rate',
        'provider_status',
        'rejection_reason',
        'is_available',
        'bio',
        'experience_years',
        'rating',
        'total_reviews',
        'kyc_document',
        'citizenship_number',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'services' => 'array',
            'hourly_rate' => 'decimal:2',
            'rating' => 'decimal:2',
            'latitude' => 'decimal:8',
            'longitude' => 'decimal:8',
            'is_available' => 'boolean',
        ];
    }

    // Role checking methods
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function isProvider(): bool
    {
        return $this->role === 'provider';
    }

    public function isCustomer(): bool
    {
        return $this->role === 'customer';
    }

    // Relationships
    public function serviceCategories(): BelongsToMany
    {
        return $this->belongsToMany(ServiceCategory::class, 'provider_services')
                    ->withPivot('price', 'description', 'is_active')
                    ->withTimestamps();
    }

    public function customerRequests(): HasMany
    {
        return $this->hasMany(ServiceRequest::class, 'customer_id');
    }

    public function providerRequests(): HasMany
    {
        return $this->hasMany(ServiceRequest::class, 'provider_id');
    }

    public function customerBookings(): HasMany
    {
        return $this->hasMany(Booking::class, 'customer_id');
    }

    public function providerBookings(): HasMany
    {
        return $this->hasMany(Booking::class, 'provider_id');
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class, 'provider_id');
    }

    public function givenReviews(): HasMany
    {
        return $this->hasMany(Review::class, 'customer_id');
    }

    // Convenience methods for customer relationships
    public function serviceRequests(): HasMany
    {
        return $this->customerRequests();
    }

    public function bookings(): HasMany
    {
        return $this->customerBookings();
    }

    public function favorites(): HasMany
    {
        return $this->hasMany(Favorite::class, 'customer_id');
    }

    public function kycDocuments(): HasMany
    {
        return $this->hasMany(KycDocument::class);
    }

    public function sentMessages(): HasMany
    {
        return $this->hasMany(ChatMessage::class, 'sender_id');
    }

    public function receivedMessages(): HasMany
    {
        return $this->hasMany(ChatMessage::class, 'receiver_id');
    }

    public function notifications(): HasMany
    {
        return $this->hasMany(Notification::class);
    }

    public function availability(): HasMany
    {
        return $this->hasMany(ProviderAvailability::class, 'provider_id');
    }

    public function serviceAreas(): HasMany
    {
        return $this->hasMany(ServiceArea::class, 'provider_id');
    }

    // Helper methods
    public function getFullAddressAttribute(): string
    {
        return $this->address ?? 'Address not provided';
    }

    public function getProfileImageUrlAttribute(): ?string
    {
        return $this->profile_image
            ? asset('storage/' . $this->profile_image)
            : null;
    }

    public function updateRating(): void
    {
        $reviews = $this->reviews()->where('is_approved', true);
        $this->rating = $reviews->avg('rating') ?? 0;
        $this->total_reviews = $reviews->count();
        $this->save();
    }

    /**
     * Check if provider has ongoing services (accepted but not completed)
     */
    public function hasOngoingServices(): bool
    {
        if ($this->role !== 'provider') {
            return false;
        }

        return $this->providerRequests()
                   ->whereIn('status', ['accepted', 'booked', 'in_progress'])
                   ->exists();
    }

    /**
     * Get dynamic availability status for providers
     */
    public function getAvailabilityStatus(): array
    {
        if ($this->role !== 'provider') {
            return [
                'status' => 'N/A',
                'message' => 'Not a service provider',
                'badge_class' => 'bg-secondary'
            ];
        }

        if ($this->hasOngoingServices()) {
            return [
                'status' => 'Unavailable',
                'message' => 'Currently working on a service',
                'badge_class' => 'bg-danger'
            ];
        }

        return [
            'status' => 'Available',
            'message' => 'Available for service',
            'badge_class' => 'bg-success'
        ];
    }

    /**
     * Get completed services for provider
     */
    public function getCompletedServices()
    {
        if ($this->role !== 'provider') {
            return collect();
        }

        return $this->providerRequests()
                   ->where('status', 'completed')
                   ->with(['customer', 'serviceCategory', 'booking.review'])
                   ->orderBy('updated_at', 'desc')
                   ->get();
    }
}
