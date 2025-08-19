<?php

namespace App\Http\Controllers\Provider;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProfileUpdateRequest;
use App\Models\ServiceCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    /**
     * Display the provider's profile edit form
     */
    public function edit()
    {
        $provider = auth()->user();
        $provider->load('serviceCategories'); // Load the relationship
        $serviceCategories = ServiceCategory::where('is_active', true)->get();

        return view('provider.profile.edit', compact('provider', 'serviceCategories'));
    }

    /**
     * Update the provider's profile
     */
    public function update(ProfileUpdateRequest $request)
    {
        $provider = auth()->user();
        $validated = $request->validated();

        // Handle profile image upload
        if ($request->hasFile('profile_image')) {
            // Delete old profile image if exists
            if ($provider->profile_image) {
                Storage::disk('public')->delete($provider->profile_image);
            }

            $profileImagePath = $request->file('profile_image')->store('profile-images', 'public');
            $validated['profile_image'] = $profileImagePath;
        }

        $provider->fill($validated);

        // Keep email verified even if email changes (no email verification required)
        if ($provider->isDirty('email')) {
            $provider->email_verified_at = now();
        }

        $provider->save();

        // Update service categories
        if ($request->has('service_categories')) {
            $provider->serviceCategories()->sync($request->service_categories);
        }

        return back()->with('success', 'Profile updated successfully!');
    }

    /**
     * Display the provider's public profile
     */
    public function show()
    {
        $provider = auth()->user();
        $provider->load(['serviceCategories', 'reviews.customer']);
        $provider->loadAvg('reviews', 'rating');
        $provider->loadCount('reviews');

        // Get recent reviews
        $recentReviews = $provider->reviews()
                                 ->with('customer')
                                 ->latest()
                                 ->take(10)
                                 ->get();

        // Get rating distribution
        $ratingDistribution = [];
        for ($i = 5; $i >= 1; $i--) {
            $ratingDistribution[$i] = $provider->reviews()
                                              ->where('rating', $i)
                                              ->count();
        }

        return view('provider.profile.show', compact('provider', 'recentReviews', 'ratingDistribution'));
    }
}
