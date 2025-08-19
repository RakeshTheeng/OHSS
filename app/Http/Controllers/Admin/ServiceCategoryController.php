<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ServiceCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ServiceCategoryController extends Controller
{
    /**
     * Display a listing of service categories
     */
    public function index(Request $request)
    {
        $query = ServiceCategory::query();

        // Search functionality
        if ($request->filled('search')) {
            $search = $request->get('search');
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        // Status filter
        if ($request->filled('status')) {
            $query->where('is_active', $request->get('status') === 'active');
        }

        $categories = $query->withCount(['serviceRequests', 'providers'])
                           ->orderBy('name')
                           ->paginate(15);

        // Statistics
        $stats = [
            'total_categories' => ServiceCategory::count(),
            'active_categories' => ServiceCategory::where('is_active', true)->count(),
            'inactive_categories' => ServiceCategory::where('is_active', false)->count(),
            'total_requests' => ServiceCategory::withCount('serviceRequests')->get()->sum('service_requests_count'),
        ];

        return view('admin.service-categories.index', compact('categories', 'stats'));
    }

    /**
     * Show the form for creating a new service category
     */
    public function create()
    {
        return view('admin.service-categories.create');
    }

    /**
     * Store a newly created service category
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:service_categories',
            'description' => 'required|string|max:1000',
            'icon' => 'required|string|max:100',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'is_active' => 'boolean',
        ]);

        // Generate slug
        $validated['slug'] = Str::slug($validated['name']);

        // Handle image upload
        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('service-categories', 'public');
        }

        ServiceCategory::create($validated);

        return redirect()->route('admin.service-categories.index')
                        ->with('success', 'Service category created successfully!');
    }

    /**
     * Display the specified service category
     */
    public function show(ServiceCategory $serviceCategory)
    {
        $serviceCategory->load(['serviceRequests.customer', 'providers']);

        return view('admin.service-categories.show', compact('serviceCategory'));
    }

    /**
     * Show the form for editing the specified service category
     */
    public function edit(ServiceCategory $serviceCategory)
    {
        return view('admin.service-categories.edit', compact('serviceCategory'));
    }

    /**
     * Update the specified service category
     */
    public function update(Request $request, ServiceCategory $serviceCategory)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:service_categories,name,' . $serviceCategory->id,
            'description' => 'required|string|max:1000',
            'icon' => 'required|string|max:100',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'is_active' => 'boolean',
        ]);

        // Generate slug
        $validated['slug'] = Str::slug($validated['name']);

        // Handle image upload
        if ($request->hasFile('image')) {
            // Delete old image
            if ($serviceCategory->image) {
                Storage::disk('public')->delete($serviceCategory->image);
            }
            $validated['image'] = $request->file('image')->store('service-categories', 'public');
        }

        $serviceCategory->update($validated);

        return redirect()->route('admin.service-categories.show', $serviceCategory)
                        ->with('success', 'Service category updated successfully!');
    }

    /**
     * Remove the specified service category
     */
    public function destroy(ServiceCategory $serviceCategory)
    {
        // Check if category has associated service requests
        if ($serviceCategory->serviceRequests()->count() > 0) {
            return redirect()->back()
                           ->with('error', 'Cannot delete category with existing service requests!');
        }

        // Delete image
        if ($serviceCategory->image) {
            Storage::disk('public')->delete($serviceCategory->image);
        }

        $serviceCategory->delete();

        return redirect()->route('admin.service-categories.index')
                        ->with('success', 'Service category deleted successfully!');
    }

    /**
     * Toggle category status
     */
    public function toggleStatus(ServiceCategory $serviceCategory)
    {
        $serviceCategory->update([
            'is_active' => !$serviceCategory->is_active
        ]);

        $status = $serviceCategory->is_active ? 'activated' : 'deactivated';

        return redirect()->back()
                        ->with('success', "Service category {$status} successfully!");
    }
}
