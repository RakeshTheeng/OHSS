<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use App\Models\ServiceCategory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        $serviceCategories = ServiceCategory::where('is_active', true)->get();

        return view('profile.edit', [
            'user' => $request->user(),
            'serviceCategories' => $serviceCategories,
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $user = $request->user();
        $validated = $request->validated();

        // Handle profile image upload
        if ($request->hasFile('profile_image')) {
            // Delete old profile image if exists
            if ($user->profile_image) {
                Storage::disk('public')->delete($user->profile_image);
            }

            $profileImagePath = $request->file('profile_image')->store('profile-images', 'public');
            $validated['profile_image'] = $profileImagePath;
        }

        // Handle KYC document upload for providers
        if ($user->role === 'provider' && $request->hasFile('kyc_document')) {
            // Delete old KYC document if exists
            if ($user->kyc_document) {
                Storage::disk('public')->delete($user->kyc_document);
            }

            $kycDocumentPath = $request->file('kyc_document')->store('kyc-documents', 'public');
            $validated['kyc_document'] = $kycDocumentPath;
        }

        $user->fill($validated);

        // Keep email verified even if email changes (no email verification required)
        if ($user->isDirty('email')) {
            $user->email_verified_at = now();
        }

        $user->save();

        // Update service categories for providers
        if ($user->role === 'provider' && $request->has('service_categories')) {
            $user->serviceCategories()->sync($request->service_categories);
        }

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}
