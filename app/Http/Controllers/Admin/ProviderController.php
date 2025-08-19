<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\ServiceCategory;
use App\Models\KycDocument;
use Illuminate\Support\Facades\Storage;

class ProviderController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'role:admin']);
    }

    /**
     * Display a listing of providers.
     */
    public function index(Request $request)
    {
        $query = User::where('role', 'provider')
                    ->with(['serviceCategories', 'kycDocuments']);

        // Filter by status
        if ($request->filled('status')) {
            $query->where('provider_status', $request->status);
        }

        // Search by name or email
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $providers = $query->latest()->paginate(15);

        $stats = [
            'total' => User::where('role', 'provider')->count(),
            'awaiting' => User::where('role', 'provider')->where('provider_status', 'awaiting')->count(),
            'approved' => User::where('role', 'provider')->where('provider_status', 'approved')->count(),
            'rejected' => User::where('role', 'provider')->where('provider_status', 'rejected')->count(),
        ];

        return view('admin.providers.index', compact('providers', 'stats'));
    }

    /**
     * Display pending providers for approval.
     */
    public function pending()
    {
        $providers = User::where('role', 'provider')
                        ->where('provider_status', 'awaiting')
                        ->with(['serviceCategories', 'kycDocuments'])
                        ->latest()
                        ->paginate(10);

        return view('admin.providers.pending', compact('providers'));
    }

    /**
     * Display the specified provider.
     */
    public function show(User $provider)
    {
        if ($provider->role !== 'provider') {
            abort(404);
        }

        $provider->load(['serviceCategories', 'kycDocuments', 'serviceRequests', 'bookings', 'reviews']);

        return view('admin.providers.show', compact('provider'));
    }

    /**
     * Approve a provider.
     */
    public function approve(User $provider)
    {
        if ($provider->role !== 'provider') {
            abort(404);
        }

        $provider->update([
            'provider_status' => 'approved',
            'rejection_reason' => null,
            'is_available' => true,
        ]);

        // TODO: Send approval notification email

        return redirect()->back()->with('success', 'Provider approved successfully!');
    }

    /**
     * Reject a provider.
     */
    public function reject(Request $request, User $provider)
    {
        if ($provider->role !== 'provider') {
            abort(404);
        }

        $request->validate([
            'rejection_reason' => 'required|string|max:500',
        ]);

        $provider->update([
            'provider_status' => 'rejected',
            'rejection_reason' => $request->rejection_reason,
            'is_available' => false,
        ]);

        // TODO: Send rejection notification email

        return redirect()->back()->with('success', 'Provider rejected with reason provided.');
    }

    /**
     * Toggle provider availability.
     */
    public function toggleAvailability(User $provider)
    {
        if ($provider->role !== 'provider' || $provider->provider_status !== 'approved') {
            abort(404);
        }

        $provider->update([
            'is_available' => !$provider->is_available,
        ]);

        $status = $provider->is_available ? 'available' : 'unavailable';
        return redirect()->back()->with('success', "Provider marked as {$status}.");
    }

    /**
     * Remove the specified provider.
     */
    public function destroy(User $provider)
    {
        if ($provider->role !== 'provider') {
            abort(404);
        }

        // Delete profile image if exists
        if ($provider->profile_image) {
            Storage::disk('public')->delete($provider->profile_image);
        }

        // Delete KYC documents
        foreach ($provider->kycDocuments as $document) {
            if ($document->file_path) {
                Storage::disk('public')->delete($document->file_path);
            }
        }

        $provider->delete();

        return redirect()->route('admin.providers.index')
                        ->with('success', 'Provider deleted successfully.');
    }
}
