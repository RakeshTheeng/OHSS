<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ServiceRequest;
use App\Models\ServiceCategory;
use App\Models\User;
use App\Models\Notification;

class ServiceRequestController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = auth()->user();

        if ($user->isCustomer()) {
            $requests = ServiceRequest::where('customer_id', $user->id)
                                    ->with(['provider', 'serviceCategory'])
                                    ->latest()
                                    ->paginate(10);
        } elseif ($user->isProvider()) {
            $requests = ServiceRequest::where('provider_id', $user->id)
                                    ->with(['customer', 'serviceCategory'])
                                    ->latest()
                                    ->paginate(10);
        } else {
            $requests = ServiceRequest::with(['customer', 'provider', 'serviceCategory'])
                                    ->latest()
                                    ->paginate(10);
        }

        return view('service-requests.index', compact('requests'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $serviceCategories = ServiceCategory::active()->ordered()->get();
        $providers = User::where('role', 'provider')
                        ->where('provider_status', 'approved')
                        ->where('is_available', true)
                        ->get();

        return view('service-requests.create', compact('serviceCategories', 'providers'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'provider_id' => 'required|exists:users,id',
            'service_category_id' => 'required|exists:service_categories,id',
            'description' => 'required|string|max:1000',
            'address' => 'required|string|max:500',
            'preferred_date' => 'nullable|date|after:now',
            'estimated_duration' => 'nullable|integer|min:30',
        ]);

        $provider = User::findOrFail($request->provider_id);

        $serviceRequest = ServiceRequest::create([
            'customer_id' => auth()->id(),
            'provider_id' => $request->provider_id,
            'service_category_id' => $request->service_category_id,
            'description' => $request->description,
            'address' => $request->address,
            'preferred_date' => $request->preferred_date,
            'estimated_duration' => $request->estimated_duration,
            'estimated_price' => $request->estimated_duration ?
                ($request->estimated_duration / 60) * $provider->hourly_rate : null,
            'status' => 'pending',
        ]);

        // Create notification for provider
        Notification::serviceRequestReceived($provider->id, $serviceRequest);

        return redirect()->route('service-requests.show', $serviceRequest)
                        ->with('success', 'Service request sent successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show(ServiceRequest $serviceRequest)
    {
        $serviceRequest->load(['customer', 'provider', 'serviceCategory', 'booking', 'chatMessages']);

        // Check authorization
        $user = auth()->user();
        if (!$user->isAdmin() &&
            $serviceRequest->customer_id !== $user->id &&
            $serviceRequest->provider_id !== $user->id) {
            abort(403);
        }

        return view('service-requests.show', compact('serviceRequest'));
    }

    /**
     * Accept a service request (Provider only)
     */
    public function accept(Request $request, ServiceRequest $serviceRequest)
    {
        $user = auth()->user();

        if (!$user->isProvider() || $serviceRequest->provider_id !== $user->id) {
            abort(403);
        }

        if (!$serviceRequest->canBeAccepted()) {
            return back()->with('error', 'This request cannot be accepted.');
        }

        $serviceRequest->accept($request->response);

        // Create notification for customer
        Notification::createForUser(
            $serviceRequest->customer_id,
            'service_request_accepted',
            'Service Request Accepted',
            "Your service request has been accepted by {$user->name}",
            ['service_request_id' => $serviceRequest->id]
        );

        return back()->with('success', 'Service request accepted successfully!');
    }

    /**
     * Reject a service request (Provider only)
     */
    public function reject(Request $request, ServiceRequest $serviceRequest)
    {
        $user = auth()->user();

        if (!$user->isProvider() || $serviceRequest->provider_id !== $user->id) {
            abort(403);
        }

        if (!$serviceRequest->canBeRejected()) {
            return back()->with('error', 'This request cannot be rejected.');
        }

        $request->validate([
            'response' => 'required|string|max:500'
        ]);

        $serviceRequest->reject($request->response);

        // Create notification for customer
        Notification::createForUser(
            $serviceRequest->customer_id,
            'service_request_rejected',
            'Service Request Rejected',
            "Your service request has been rejected by {$user->name}",
            ['service_request_id' => $serviceRequest->id]
        );

        return back()->with('success', 'Service request rejected.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ServiceRequest $serviceRequest)
    {
        $user = auth()->user();

        if (!$user->isAdmin() && $serviceRequest->customer_id !== $user->id) {
            abort(403);
        }

        if ($serviceRequest->status !== 'pending') {
            return back()->with('error', 'Only pending requests can be deleted.');
        }

        $serviceRequest->delete();

        return redirect()->route('service-requests.index')
                        ->with('success', 'Service request deleted successfully.');
    }
}
