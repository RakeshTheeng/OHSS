<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ServiceArea extends Model
{
    use HasFactory;

    protected $fillable = [
        'provider_id',
        'area_name',
        'latitude',
        'longitude',
        'radius_km',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'latitude' => 'decimal:8',
            'longitude' => 'decimal:8',
            'is_active' => 'boolean',
        ];
    }

    // Relationships
    public function provider(): BelongsTo
    {
        return $this->belongsTo(User::class, 'provider_id');
    }

    // Helper methods
    public function isWithinServiceArea(float $lat, float $lng): bool
    {
        $distance = $this->calculateDistance($lat, $lng, $this->latitude, $this->longitude);
        return $distance <= $this->radius_km;
    }

    private function calculateDistance(float $lat1, float $lng1, float $lat2, float $lng2): float
    {
        $earthRadius = 6371; // Earth's radius in kilometers

        $dLat = deg2rad($lat2 - $lat1);
        $dLng = deg2rad($lng2 - $lng1);

        $a = sin($dLat/2) * sin($dLat/2) +
             cos(deg2rad($lat1)) * cos(deg2rad($lat2)) *
             sin($dLng/2) * sin($dLng/2);

        $c = 2 * atan2(sqrt($a), sqrt(1-$a));

        return $earthRadius * $c;
    }

    public function getFormattedLocation(): string
    {
        return $this->area_name . ' (' . $this->latitude . ', ' . $this->longitude . ')';
    }
}
