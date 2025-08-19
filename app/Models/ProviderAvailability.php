<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProviderAvailability extends Model
{
    use HasFactory;

    protected $fillable = [
        'provider_id',
        'day_of_week',
        'start_time',
        'end_time',
        'is_available',
    ];

    protected function casts(): array
    {
        return [
            'start_time' => 'datetime:H:i',
            'end_time' => 'datetime:H:i',
            'is_available' => 'boolean',
        ];
    }

    // Relationships
    public function provider(): BelongsTo
    {
        return $this->belongsTo(User::class, 'provider_id');
    }

    // Helper methods
    public function isAvailableAt(\DateTime $dateTime): bool
    {
        $dayOfWeek = strtolower($dateTime->format('l'));
        $time = $dateTime->format('H:i');

        return $this->day_of_week === $dayOfWeek &&
               $this->is_available &&
               $time >= $this->start_time &&
               $time <= $this->end_time;
    }

    public function getFormattedTimeRange(): string
    {
        return $this->start_time . ' - ' . $this->end_time;
    }
}
