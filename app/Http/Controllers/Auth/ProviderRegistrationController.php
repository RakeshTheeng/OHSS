<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\ServiceCategory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rules;

class ProviderRegistrationController extends Controller
{
    public function create()
    {
        $serviceCategories = ServiceCategory::active()->get();
        return view('auth.provider-register', compact('serviceCategories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'phone' => ['required', 'string', 'max:20'],
            'address' => ['required', 'string', 'max:500'],
            'hourly_rate' => ['required', 'numeric', 'min:100', 'max:10000'],
            'service_categories' => ['required', 'array', 'min:1'],
            'service_categories.*' => ['exists:service_categories,id'],
            'bio' => ['nullable', 'string', 'max:1000'],
            'experience_years' => ['required', 'integer', 'min:0', 'max:50'],
            'profile_image' => ['nullable', 'image', 'mimes:jpeg,png,jpg', 'max:2048'],
        ]);

        // Handle profile image upload
        $profileImagePath = null;
        if ($request->hasFile('profile_image')) {
            $profileImagePath = $request->file('profile_image')->store('profile-images', 'public');
        }

        // Create provider user
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'provider',
            'phone' => $request->phone,
            'address' => $request->address,
            'hourly_rate' => $request->hourly_rate,
            'provider_status' => 'awaiting',
            'is_available' => false,
            'profile_image' => $profileImagePath,
            'bio' => $request->bio,
            'experience_years' => $request->experience_years,
            'email_verified_at' => now(), // Auto-verify email
        ]);

        // Attach service categories
        $user->serviceCategories()->attach($request->service_categories);

        return redirect()->route('login')->with('info',
            'Registration submitted successfully. Your account is pending approval by admin.');
    }
}
