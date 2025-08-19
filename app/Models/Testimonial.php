<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Testimonial extends Model
{
    use HasFactory;

    protected $fillable = [
        'review_id',
        'customer_id',
        'provider_id',
        'rating',
        'comment',
        'customer_name',
        'provider_name',
        'service_category',
        'is_featured',
        'is_active',
    ];

    protected $casts = [
        'is_featured' => 'boolean',
        'is_active' => 'boolean',
        'rating' => 'integer',
    ];

    /**
     * Get the review that this testimonial is based on
     */
    public function review()
    {
        return $this->belongsTo(Review::class);
    }

    /**
     * Get the customer who wrote this testimonial
     */
    public function customer()
    {
        return $this->belongsTo(User::class, 'customer_id');
    }

    /**
     * Get the provider this testimonial is about
     */
    public function provider()
    {
        return $this->belongsTo(User::class, 'provider_id');
    }

    /**
     * Scope for active testimonials
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope for featured testimonials
     */
    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    /**
     * Scope for high-rated testimonials (4+ stars)
     */
    public function scopeHighRated($query)
    {
        return $query->where('rating', '>=', 4);
    }

    /**
     * Get testimonials for homepage display
     */
    public static function getForHomepage($limit = 6)
    {
        return self::active()
                  ->highRated()
                  ->with(['customer', 'provider'])
                  ->orderBy('is_featured', 'desc')
                  ->orderBy('rating', 'desc')
                  ->orderBy('created_at', 'desc')
                  ->limit($limit)
                  ->get();
    }

    /**
     * Get testimonials for a specific provider
     */
    public static function getForProvider($providerId, $limit = 10)
    {
        return self::active()
                  ->where('provider_id', $providerId)
                  ->with(['customer'])
                  ->orderBy('rating', 'desc')
                  ->orderBy('created_at', 'desc')
                  ->limit($limit)
                  ->get();
    }

    /**
     * Get testimonials by service category
     */
    public static function getByCategory($category, $limit = 5)
    {
        return self::active()
                  ->highRated()
                  ->where('service_category', $category)
                  ->with(['customer', 'provider'])
                  ->orderBy('rating', 'desc')
                  ->orderBy('created_at', 'desc')
                  ->limit($limit)
                  ->get();
    }

    /**
     * Get the time ago formatted string
     */
    public function getTimeAgoAttribute()
    {
        return $this->created_at->diffForHumans();
    }

    /**
     * Get the star rating HTML
     */
    public function getStarRatingAttribute()
    {
        $stars = '';
        for ($i = 1; $i <= 5; $i++) {
            if ($i <= $this->rating) {
                $stars .= '<i class="fas fa-star text-warning"></i>';
            } else {
                $stars .= '<i class="far fa-star text-muted"></i>';
            }
        }
        return $stars;
    }

    /**
     * Get customer initials for avatar
     */
    public function getCustomerInitialsAttribute()
    {
        $names = explode(' ', $this->customer_name);
        $initials = '';
        foreach ($names as $name) {
            $initials .= strtoupper(substr($name, 0, 1));
        }
        return substr($initials, 0, 2);
    }

    /**
     * Get a short version of the comment
     */
    public function getShortCommentAttribute()
    {
        return \Str::limit($this->comment, 100);
    }

    /**
     * Check if testimonial is recent (within last 30 days)
     */
    public function getIsRecentAttribute()
    {
        return $this->created_at->diffInDays(now()) <= 30;
    }
}
