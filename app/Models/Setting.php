<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Setting extends Model
{
    use HasFactory;

    protected $fillable = [
        'key',
        'value',
        'type',
        'description'
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get a setting value by key
     */
    public static function get($key, $default = null)
    {
        $settings = Cache::remember('app_settings', 3600, function () {
            return self::pluck('value', 'key')->toArray();
        });

        return $settings[$key] ?? $default;
    }

    /**
     * Set a setting value
     */
    public static function set($key, $value, $type = 'string', $description = null)
    {
        self::updateOrCreate(
            ['key' => $key],
            [
                'value' => $value,
                'type' => $type,
                'description' => $description
            ]
        );

        // Clear cache
        Cache::forget('app_settings');

        return true;
    }

    /**
     * Get all settings as key-value pairs
     */
    public static function getAllSettings()
    {
        return Cache::remember('app_settings', 3600, function () {
            return self::pluck('value', 'key')->toArray();
        });
    }

    /**
     * Clear settings cache
     */
    public static function clearCache()
    {
        Cache::forget('app_settings');
    }

    /**
     * Get settings by type
     */
    public static function getByType($type)
    {
        return self::where('type', $type)->pluck('value', 'key')->toArray();
    }

    /**
     * Check if a setting exists
     */
    public static function has($key)
    {
        return self::where('key', $key)->exists();
    }

    /**
     * Delete a setting
     */
    public static function remove($key)
    {
        $deleted = self::where('key', $key)->delete();
        
        if ($deleted) {
            Cache::forget('app_settings');
        }

        return $deleted;
    }

    /**
     * Get boolean setting value
     */
    public static function getBool($key, $default = false)
    {
        $value = self::get($key, $default);
        
        if (is_string($value)) {
            return in_array(strtolower($value), ['1', 'true', 'yes', 'on']);
        }
        
        return (bool) $value;
    }

    /**
     * Get integer setting value
     */
    public static function getInt($key, $default = 0)
    {
        return (int) self::get($key, $default);
    }

    /**
     * Get float setting value
     */
    public static function getFloat($key, $default = 0.0)
    {
        return (float) self::get($key, $default);
    }

    /**
     * Get array setting value (JSON decoded)
     */
    public static function getArray($key, $default = [])
    {
        $value = self::get($key, $default);
        
        if (is_string($value)) {
            $decoded = json_decode($value, true);
            return $decoded !== null ? $decoded : $default;
        }
        
        return is_array($value) ? $value : $default;
    }

    /**
     * Set array setting value (JSON encoded)
     */
    public static function setArray($key, $value, $description = null)
    {
        return self::set($key, json_encode($value), 'array', $description);
    }
}
