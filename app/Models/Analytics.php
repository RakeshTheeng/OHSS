<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Analytics extends Model
{
    use HasFactory;

    protected $fillable = [
        'metric_name',
        'metric_type',
        'value',
        'date',
        'category',
        'metadata',
    ];

    protected function casts(): array
    {
        return [
            'value' => 'decimal:2',
            'date' => 'date',
            'metadata' => 'array',
        ];
    }

    // Static methods for recording analytics
    public static function recordMetric(string $metricName, string $metricType, float $value, ?string $category = null, ?array $metadata = null, ?Carbon $date = null): self
    {
        return self::create([
            'metric_name' => $metricName,
            'metric_type' => $metricType,
            'value' => $value,
            'date' => $date ?? now()->toDateString(),
            'category' => $category,
            'metadata' => $metadata,
        ]);
    }

    public static function getDailyMetric(string $metricName, Carbon $date): float
    {
        return self::where('metric_name', $metricName)
                  ->where('date', $date->toDateString())
                  ->sum('value');
    }

    public static function getMetricTrend(string $metricName, int $days = 30): array
    {
        $endDate = now();
        $startDate = $endDate->copy()->subDays($days);

        return self::where('metric_name', $metricName)
                  ->whereBetween('date', [$startDate->toDateString(), $endDate->toDateString()])
                  ->orderBy('date')
                  ->get()
                  ->groupBy('date')
                  ->map(function ($items) {
                      return $items->sum('value');
                  })
                  ->toArray();
    }

    public static function getTopCategories(string $metricName, int $limit = 10): array
    {
        return self::where('metric_name', $metricName)
                  ->whereNotNull('category')
                  ->selectRaw('category, SUM(value) as total')
                  ->groupBy('category')
                  ->orderByDesc('total')
                  ->limit($limit)
                  ->pluck('total', 'category')
                  ->toArray();
    }
}
