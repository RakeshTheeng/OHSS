<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rules;

class ProfileController extends Controller
{
    /**
     * Show the form for editing the customer's profile
     */
    public function edit()
    {
        $customer = auth()->user();

        // Load relationships for stats
        $customer->load(['serviceRequests', 'bookings', 'givenReviews', 'favorites']);

        return view('customer.profile.edit', compact('customer'));
    }

    /**
     * Update the customer's profile
     */
    public function update(Request $request)
    {
        $customer = auth()->user();

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:users,email,' . $customer->id],
            'phone' => ['nullable', 'string', 'max:20'],
            'address' => ['nullable', 'string', 'max:500'],
            'profile_image' => ['nullable', 'image', 'mimes:jpeg,png,jpg', 'max:2048'],
        ]);

        // Handle profile image upload
        if ($request->hasFile('profile_image')) {
            // Delete old profile image if exists
            if ($customer->profile_image) {
                Storage::disk('public')->delete($customer->profile_image);
            }

            $profileImagePath = $request->file('profile_image')->store('profile-images', 'public');
            $validated['profile_image'] = $profileImagePath;
        }

        $customer->fill($validated);

        // Keep email verified even if email changes (no email verification required)
        if ($customer->isDirty('email')) {
            $customer->email_verified_at = now();
        }

        $customer->save();

        return redirect()->route('customer.profile.edit')
                        ->with('success', 'Profile updated successfully!');
    }
}
