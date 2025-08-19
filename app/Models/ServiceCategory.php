<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ServiceCategory extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'icon',
        'image',
        'is_active',
        'sort_order',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
        ];
    }

    // Relationships
    public function providers(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'provider_services')
                    ->withPivot('price', 'description', 'is_active')
                    ->withTimestamps();
    }

    public function serviceRequests(): HasMany
    {
        return $this->hasMany(ServiceRequest::class);
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order');
    }

    // Helper methods
    public function getImageUrlAttribute(): ?string
    {
        return $this->image
            ? asset('storage/' . $this->image)
            : null;
    }

    public function getIconUrlAttribute(): ?string
    {
        return $this->icon
            ? asset('storage/' . $this->icon)
            : null;
    }
}
