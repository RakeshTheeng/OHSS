<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\ServiceCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    /**
     * Display a listing of users by type
     */
    public function index(Request $request)
    {
        $type = $request->get('type', 'all'); // all, customers, providers

        $query = User::query();

        // Filter by user type
        switch ($type) {
            case 'customers':
                $query->where('role', 'customer');
                break;
            case 'providers':
                $query->where('role', 'provider');
                break;
            case 'all':
            default:
                $query->whereIn('role', ['customer', 'provider']);
                break;
        }

        // Search functionality
        if ($request->filled('search')) {
            $search = $request->get('search');
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        // Status filter
        if ($request->filled('status')) {
            $query->where('status', $request->get('status'));
        }

        // Provider status filter
        if ($request->filled('provider_status') && $type !== 'customers') {
            $query->where('provider_status', $request->get('provider_status'));
        }

        $users = $query->latest()->paginate(15);

        // Statistics
        $stats = [
            'total_users' => User::whereIn('role', ['customer', 'provider'])->count(),
            'total_customers' => User::where('role', 'customer')->count(),
            'total_providers' => User::where('role', 'provider')->count(),
            'active_users' => User::whereIn('role', ['customer', 'provider'])->where('status', 'active')->count(),
            'pending_providers' => User::where('role', 'provider')->where('provider_status', 'pending')->count(),
            'approved_providers' => User::where('role', 'provider')->where('provider_status', 'approved')->count(),
        ];

        return view('admin.users.index', compact('users', 'stats', 'type'));
    }

    /**
     * Display customers only
     */
    public function customers(Request $request)
    {
        $request->merge(['type' => 'customers']);
        return $this->index($request);
    }

    /**
     * Display providers only
     */
    public function providers(Request $request)
    {
        $request->merge(['type' => 'providers']);
        return $this->index($request);
    }

    /**
     * Show the form for creating a new user
     */
    public function create(Request $request)
    {
        $type = $request->get('type', 'customer');
        $serviceCategories = ServiceCategory::where('is_active', true)->get();

        return view('admin.users.create', compact('type', 'serviceCategories'));
    }

    /**
     * Store a newly created user
     */
    public function store(Request $request)
    {
        $rules = [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:500',
            'role' => 'required|in:customer,provider',
            'status' => 'required|in:active,inactive',
        ];

        // Additional validation for providers
        if ($request->role === 'provider') {
            $rules = array_merge($rules, [
                'hourly_rate' => 'required|numeric|min:0',
                'experience_years' => 'required|integer|min:0',
                'bio' => 'nullable|string|max:1000',
                'service_categories' => 'required|array|min:1',
                'service_categories.*' => 'exists:service_categories,id',
                'provider_status' => 'required|in:pending,approved,rejected',
            ]);
        }

        $validated = $request->validate($rules);

        // Handle profile image upload
        if ($request->hasFile('profile_image')) {
            $validated['profile_image'] = $request->file('profile_image')->store('profile-images', 'public');
        }

        // Hash password
        $validated['password'] = Hash::make($validated['password']);

        // Set email verification
        $validated['email_verified_at'] = now();

        $user = User::create($validated);

        // Attach service categories for providers
        if ($request->role === 'provider' && $request->filled('service_categories')) {
            $user->serviceCategories()->attach($request->service_categories);
        }

        return redirect()->route('admin.users.index', ['type' => $request->role === 'provider' ? 'providers' : 'customers'])
                        ->with('success', ucfirst($request->role) . ' created successfully!');
    }

    /**
     * Display the specified user
     */
    public function show(User $user)
    {
        // Load relationships based on user role
        if ($user->role === 'customer') {
            $user->load('customerRequests', 'customerBookings', 'reviews');
        } elseif ($user->role === 'provider') {
            $user->load('serviceCategories', 'providerRequests', 'providerBookings', 'reviews');
        } else {
            $user->load('reviews');
        }

        return view('admin.users.show', compact('user'));
    }

    /**
     * Show the form for editing the specified user
     */
    public function edit(User $user)
    {
        $serviceCategories = ServiceCategory::where('is_active', true)->get();

        return view('admin.users.edit', compact('user', 'serviceCategories'));
    }

    /**
     * Update the specified user
     */
    public function update(Request $request, User $user)
    {
        $rules = [
            'name' => 'required|string|max:255',
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:500',
            'status' => 'required|in:active,inactive',
        ];

        // Additional validation for providers
        if ($user->role === 'provider') {
            $rules = array_merge($rules, [
                'hourly_rate' => 'required|numeric|min:0',
                'experience_years' => 'required|integer|min:0',
                'bio' => 'nullable|string|max:1000',
                'service_categories' => 'required|array|min:1',
                'service_categories.*' => 'exists:service_categories,id',
                'provider_status' => 'required|in:pending,approved,rejected',
                'is_available' => 'boolean',
            ]);
        }

        $validated = $request->validate($rules);

        // Handle profile image upload
        if ($request->hasFile('profile_image')) {
            // Delete old image
            if ($user->profile_image) {
                Storage::disk('public')->delete($user->profile_image);
            }
            $validated['profile_image'] = $request->file('profile_image')->store('profile-images', 'public');
        }

        // Update password if provided
        if ($request->filled('password')) {
            $request->validate(['password' => 'string|min:8|confirmed']);
            $validated['password'] = Hash::make($request->password);
        }

        $user->update($validated);

        // Update service categories for providers
        if ($user->role === 'provider' && $request->filled('service_categories')) {
            $user->serviceCategories()->sync($request->service_categories);
        }

        return redirect()->route('admin.users.show', $user)
                        ->with('success', 'User updated successfully!');
    }

    /**
     * Remove the specified user
     */
    public function destroy(User $user)
    {
        // Prevent deletion of admin users
        if ($user->role === 'admin') {
            return redirect()->back()->with('error', 'Cannot delete admin users!');
        }

        // Delete profile image
        if ($user->profile_image) {
            Storage::disk('public')->delete($user->profile_image);
        }

        $user->delete();

        return redirect()->route('admin.users.index')
                        ->with('success', 'User deleted successfully!');
    }

    /**
     * Toggle user status
     */
    public function toggleStatus(User $user)
    {
        $user->update([
            'status' => $user->status === 'active' ? 'inactive' : 'active'
        ]);

        return redirect()->back()
                        ->with('success', 'User status updated successfully!');
    }

    /**
     * Approve provider
     */
    public function approveProvider(User $user)
    {
        if ($user->role !== 'provider') {
            return redirect()->back()->with('error', 'Only providers can be approved!');
        }

        $user->update([
            'provider_status' => 'approved',
            'status' => 'active'
        ]);

        return redirect()->back()
                        ->with('success', 'Provider approved successfully!');
    }

    /**
     * Reject provider
     */
    public function rejectProvider(User $user)
    {
        if ($user->role !== 'provider') {
            return redirect()->back()->with('error', 'Only providers can be rejected!');
        }

        $user->update([
            'provider_status' => 'rejected'
        ]);

        return redirect()->back()
                        ->with('success', 'Provider rejected successfully!');
    }

    /**
     * Reset user password
     */
    public function resetPassword(User $user)
    {
        // Generate a new random password
        $newPassword = Str::random(12);

        $user->update([
            'password' => Hash::make($newPassword)
        ]);

        // In a real application, you would send this password via email
        // For now, we'll just show it in the session flash message
        return redirect()->back()
                        ->with('success', "Password reset successfully! New password: {$newPassword}")
                        ->with('new_password', $newPassword);
    }
}
