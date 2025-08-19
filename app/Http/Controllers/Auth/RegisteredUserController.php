<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\ServiceCategory;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        $serviceCategories = ServiceCategory::where('is_active', true)->get();
        return view('auth.register', compact('serviceCategories'));
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $validationRules = [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'role' => ['required', 'in:customer,provider'],
            'phone' => ['nullable', 'string', 'max:20'],
            'address' => ['nullable', 'string', 'max:500'],
            'profile_image' => ['nullable', 'image', 'mimes:jpeg,png,jpg', 'max:2048'],
        ];

        // Additional validation for providers
        if ($request->role === 'provider') {
            $validationRules['hourly_rate'] = ['required', 'numeric', 'min:100', 'max:10000'];
            $validationRules['experience_years'] = ['required', 'integer', 'min:0', 'max:50'];
            $validationRules['bio'] = ['required', 'string', 'min:50', 'max:1000'];
            $validationRules['kyc_document'] = ['required', 'file', 'mimes:pdf,jpeg,png,jpg', 'max:5120'];
            $validationRules['citizenship_number'] = ['required', 'string', 'max:50'];
            $validationRules['service_categories'] = ['required', 'array', 'min:1'];
            $validationRules['service_categories.*'] = ['exists:service_categories,id'];
        }

        $request->validate($validationRules);

        $userData = [
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
            'phone' => $request->phone,
            'address' => $request->address,
            'email_verified_at' => now(), // Auto-verify email
        ];

        // Handle profile image upload
        if ($request->hasFile('profile_image')) {
            $profileImagePath = $request->file('profile_image')->store('profile-images', 'public');
            $userData['profile_image'] = $profileImagePath;
        }

        // Add provider-specific data
        if ($request->role === 'provider') {
            $userData['hourly_rate'] = $request->hourly_rate;
            $userData['experience_years'] = $request->experience_years;
            $userData['bio'] = $request->bio;
            $userData['citizenship_number'] = $request->citizenship_number;
            $userData['provider_status'] = 'awaiting';
            $userData['is_available'] = false; // Will be enabled after approval

            // Handle KYC document upload
            if ($request->hasFile('kyc_document')) {
                $kycDocumentPath = $request->file('kyc_document')->store('kyc-documents', 'public');
                $userData['kyc_document'] = $kycDocumentPath;
            }
        }

        $user = User::create($userData);

        // Attach service categories for providers
        if ($request->role === 'provider' && $request->service_categories) {
            $user->serviceCategories()->attach($request->service_categories);
        }

        event(new Registered($user));

        // Different flows for customers and providers
        if ($request->role === 'customer') {
            // Customer registration: redirect to login with success message
            return redirect()->route('login')->with('success',
                'Registration successful. Please login to continue.');
        } else {
            // Provider registration: redirect to login with pending approval message
            return redirect()->route('login')->with('info',
                'Registration submitted successfully. Your account is pending approval by admin.');
        }
    }
}
